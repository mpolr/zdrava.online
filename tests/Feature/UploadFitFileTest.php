<?php

namespace Tests\Feature;

use App\Jobs\ProcessFitFile;
use App\Models\Activities;
use App\Models\User;
use Bus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;

class UploadFitFileTest extends TestCase
{
    use RefreshDatabase;

    public function testFitFileUploadAndProcessing(): void
    {
        $this->seed();

        // Имитируем файловую систему
        Storage::fake('local');

        // Имитируем очередь заданий
        Queue::fake();

        // Создаем тестового пользователя
        $user = User::factory()->create();

        // Указываем путь к реальному FIT-файлу
        $fitFilePath = base_path('tests/Fixtures/MyWhoosh_Parramatta.fit');
        $fitFile = new UploadedFile(
            $fitFilePath,
            'MyWhoosh_Parramatta.fit',
            'application/octet-stream',
            null,
            true
        );

        // Отправляем запрос на загрузку файла
        $response = $this->actingAs($user)->post(route('upload.workout'), [
            'workout' => [$fitFile],
        ]);

        // Проверяем успешный ответ
        $response->assertStatus(302); // Редирект при успехе
        $response->assertSessionHas('success', __('File Upload successfully'));

        // Проверяем, что файл был сохранен в хранилище с корректным расширением
        $realHashName = str_replace('.bin', '.fit', $fitFile->hashName());
        $fileName = 'temp/' . $realHashName;
        Storage::assertExists($fileName);

        // Проверяем, что задание ProcessFitFile было отправлено в очередь
        Queue::assertPushed(ProcessFitFile::class, static function ($job) use ($user, $realHashName) {
            $reflection = new \ReflectionClass($job);
            $activityProperty = $reflection->getProperty('activity');
            $activity = $activityProperty->getValue($job);

            $filenameProperty = $reflection->getProperty('fileName');
            $filename = $filenameProperty->getValue($job);

            return $activity->user_id === $user->id && $filename === $realHashName;
        });

        // Выполняем все задания из очереди (имитация завершения обработки)
        Bus::dispatchNow(new ProcessFitFile(Activities::first(), $realHashName));

        // Проверяем, что запись Activity была добавлена в базу данных
        $this->assertDatabaseHas('activities', [
            'user_id' => $user->id,
            'file' => $realHashName,
        ]);
    }
}
