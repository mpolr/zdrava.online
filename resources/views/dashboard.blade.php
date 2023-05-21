@extends('layouts.site')
@section('content')
    <main class="container mx-auto px-0 py-12">
        <div class="flex">
            <div class="w-2/4 lg:w-4/12 px-4">
                <!-- Боковое меню -->
                <div
                    class="block rounded-lg bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700">
                    <div
                        class="relative overflow-hidden bg-cover bg-no-repeat"
                        data-te-ripple-init
                        data-te-ripple-color="light">
                        <img
                            class="rounded-t-lg"
                            src="https://tecdn.b-cdn.net/img/new/standard/nature/186.jpg"
                            alt="" />
                        <a href="#!">
                            <div
                                class="absolute bottom-0 left-0 right-0 top-0 h-full w-full overflow-hidden bg-[hsla(0,0%,98%,0.15)] bg-fixed opacity-0 transition duration-300 ease-in-out hover:opacity-100"></div>
                        </a>
                    </div>
                    <div class="p-6">
                        <h5
                            class="mb-2 text-xl font-medium leading-tight text-neutral-800 dark:text-neutral-50">
                            Card title
                        </h5>
                        <p class="mb-4 text-base text-neutral-600 dark:text-neutral-200">
                            Some quick example text to build on the card title and make up the
                            bulk of the card's content.
                        </p>
                        <button
                            type="button"
                            class="inline-block rounded bg-primary px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]"
                            data-te-ripple-init
                            data-te-ripple-color="light">
                            Button
                        </button>
                    </div>
                </div>
            </div>
            <div class="w-2/4">
                <!-- Контент и графики -->
                <p>
                    Lorem ipsum, dolor sit amet consectetur adipisicing elit. Eligendi harum tempore
                    cupiditate asperiores provident, itaque, quo ex iusto rerum voluptatum delectus corporis
                    quisquam maxime a ipsam nisi sapiente qui optio! Dignissimos harum quod culpa officiis
                    suscipit soluta labore! Expedita quas, nesciunt similique autem, sunt, doloribus pariatur
                    maxime qui sint id enim. Placeat, maxime labore. Dolores ex provident ipsa impedit, omnis
                    magni earum. Sed fuga ex ducimus consequatur corporis, architecto nesciunt vitae ipsum
                    consequuntur perspiciatis nulla esse voluptatem quos dolorum delectus similique eum vero
                    in est velit quasi pariatur blanditiis incidunt quam.
                </p>
            </div>
            <div class="w-1/4 lg:w-3/12 px-4">
                <!-- Боковое меню -->
                <p>
                    Lorem ipsum, dolor sit amet consectetur adipisicing elit. Eligendi harum tempore
                    cupiditate asperiores provident, itaque, quo ex iusto rerum voluptatum delectus corporis
                    quisquam maxime a ipsam nisi sapiente qui optio! Dignissimos harum quod culpa officiis
                    suscipit soluta labore! Expedita quas, nesciunt similique autem, sunt, doloribus pariatur
                    maxime qui sint id enim. Placeat, maxime labore. Dolores ex provident ipsa impedit, omnis
                    magni earum. Sed fuga ex ducimus consequatur corporis, architecto nesciunt vitae ipsum
                    consequuntur perspiciatis nulla esse voluptatem quos dolorum delectus similique eum vero
                    in est velit quasi pariatur blanditiis incidunt quam.
                </p>
            </div>
        </div>
    </main>
@endsection
