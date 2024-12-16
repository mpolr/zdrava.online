import * as Ant from "ant-plus-next";

document.addEventListener('DOMContentLoaded', () => {
    const connectButton = document.getElementById('connect');
    const disconnectButton = document.getElementById('disconnect');
    const stickStatus = document.getElementById('stick-status');

    const TIMEOUT_DURATION = 5000; // 5 секунд без данных

    const sensors = {
        heartRateSensor: {
            instance: null,
            statusElement: document.getElementById('heart-rate-status'),
            valueElement: document.getElementById('heart-rate-value'),
            displayElement: document.getElementById('heart-rate-display'),
            event: 'heartRateData',
            dataHandler: (data) => `${data.ComputedHeartRate} bpm`,
            timeout: null
        },
            cadenceSensor: {
            instance: null,
            statusElement: document.getElementById('cadence-status'),
            valueElement: document.getElementById('cadence-value'),
            displayElement: document.getElementById('cadence-display'),
            event: 'cadenceData',
            dataHandler: (data) => {
                if (data.CalculatedCadence > 999) {
                    return `0 rpm`;
                } else {
                    return `${Math.round(data.CalculatedCadence)} rpm`;
                }
            },
            timeout: null
        },
        powerSensor: {
            instance: null,
            statusElement: document.getElementById('power-status'),
            valueElement: document.getElementById('power-value'),
            displayElement: document.getElementById('power-display'),
            event: 'powerData',
            dataHandler: (data) => `${data.Power} W`,
            timeout: null
        }
    };

    let webUsbStick = null;

    const resetTimeout = (sensorKey) => {
        const sensor = sensors[sensorKey];
        if (sensor.timeout) {
            clearTimeout(sensor.timeout);
        }
        sensor.timeout = setTimeout(() => {
            console.warn(`${sensorKey} lost connection or stopped transmitting data.`);
            updateSensorStatus(sensorKey, false);
        }, TIMEOUT_DURATION);
    };

    const updateStickStatus = (isConnected) => {
        stickStatus.textContent = isConnected ? 'Подключен' : 'Отключен';
        stickStatus.className = isConnected ? 'text-green-600' : 'text-red-600';
    };

    const updateSensorStatus = (sensorKey, isConnected, value = null) => {
        const sensor = sensors[sensorKey];

        if (isConnected) {
            sensor.statusElement.textContent = 'Подключен';
            sensor.statusElement.className = 'text-green-600';
            sensor.displayElement.classList.remove('hidden');

            if (value !== null) {
                sensor.valueElement.textContent = value;
            }

            resetTimeout(sensorKey);
        } else {
            sensor.statusElement.textContent = 'Отключен';
            sensor.statusElement.className = 'text-red-600';
            sensor.displayElement.classList.add('hidden');
        }
    };

    connectButton.addEventListener('click', async () => {
        if (!navigator.usb) {
            alert("WebUSB not supported in this browser.");
        }

        if (webUsbStick) {
            console.warn("Stick already connected!");
            return;
        }

        webUsbStick = new Ant.WebUsbStick();
        sensors.heartRateSensor.instance = new Ant.HeartRateSensor(webUsbStick);
        sensors.cadenceSensor.instance = new Ant.CadenceSensor(webUsbStick);
        sensors.powerSensor.instance = new Ant.BicyclePowerSensor(webUsbStick);

        for (const [key, sensor] of Object.entries(sensors)) {
            const instance = sensor.instance;
            instance.on(sensor.event, (data) => {
                const formattedValue = sensor.dataHandler(data);
                updateSensorStatus(key, true, formattedValue);
            });

            instance.on('searchTimeout', () => {
                console.warn(`${key} search timed out.`);
                updateSensorStatus(key, false);
            });
        }

        webUsbStick.on('startup', async () => {
            console.log("ANT+ Stick initialized successfully.");
            updateStickStatus(true);

            try {
                await sensors.heartRateSensor.instance.attach(0, 0);
                console.log("Heart Rate Sensor attached successfully.");
                await sensors.cadenceSensor.instance.attach(1, 0);
                console.log("Cadence Sensor attached successfully.");
                await sensors.powerSensor.instance.attach(2, 0);
                console.log("Power Sensor attached successfully.");
            } catch (err) {
                console.error("Failed to attach sensors:", err);
            }
        });

        webUsbStick.on('error', (err) => {
            console.error("ANT+ Stick error:", err);
            updateStickStatus(false);
        });

        try {
            const result = await webUsbStick.open();
            if (!result) {
                console.error("WebUSB Stick not found!");
                updateStickStatus(false);
            } else {
                console.log("ANT+ Stick is opening...");
            }
        } catch (err) {
            console.error("Error opening ANT+ Stick:", err);
        }
    });

    disconnectButton.addEventListener('click', async () => {
        if (!webUsbStick) {
            console.warn("Stick is not connected!");
            return;
        }

        try {
            for (const [key, sensor] of Object.entries(sensors)) {
                if (sensor.instance) {
                    await sensor.instance.detach();
                    console.log(`${key} detached successfully.`);
                    updateSensorStatus(key, false);
                }
            }

            await webUsbStick.close();
            console.log("ANT+ Stick disconnected successfully.");
            webUsbStick = null;

            updateStickStatus(false);
        } catch (err) {
            console.error("Error during disconnect:", err);
        }
    });
});
