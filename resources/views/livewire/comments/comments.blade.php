<div>
    <div class="container mx-auto mt-0 items-left">
        <!-- Отображение комментариев -->
        <div class="space-y-4">
            <!-- Цикл по массиву комментариев -->
            @foreach ($comments as $comment)
                <div class="flex-col w-full py-4 bg-white border-b-2 border-r-2 border-gray-200 sm:px-4 sm:py-4 md:px-4 sm:rounded-lg sm:shadow-sm">
                    <div class="flex flex-row">
                        <img class="object-cover w-12 h-12 border-2 border-gray-300 rounded-full"
                             alt="{{ $comment['author'] }}'s avatar"
                             src="{{ $comment['photo'] }}">
                        <div class="flex-col mt-1">
                            <div class="flex items-center flex-1 px-4 font-bold leading-tight">
                                <a href="{{ route('athlete.profile', $comment['userId']) }}">{{ $comment['author'] }}</a>
                                <span class="ml-2 text-xs font-normal text-gray-500">{{ $comment['date'] }}</span>
                            </div>
                            <div class="flex-1 px-2 ml-2 text-sm font-medium leading-loose text-gray-600">
                                {{ $comment['text'] }}
                            </div>
                            @if (!$onlyLast)
                            <button wire:click="replyToComment({{ $comment['id'] }})" title="{{ __('Reply') }}" class="inline-flex items-center px-1 pt-2 ml-1 flex-column">
                                <svg class="w-5 h-5 ml-2 text-gray-600 cursor-pointer fill-current hover:text-gray-900"
                                     viewBox="0 0 95 78" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M29.58 0c1.53.064 2.88 1.47 2.879 3v11.31c19.841.769 34.384 8.902 41.247 20.464 7.212 12.15 5.505 27.83-6.384 40.273-.987 1.088-2.82 1.274-4.005.405-1.186-.868-1.559-2.67-.814-3.936 4.986-9.075 2.985-18.092-3.13-24.214-5.775-5.78-15.377-8.782-26.914-5.53V53.99c-.01 1.167-.769 2.294-1.848 2.744-1.08.45-2.416.195-3.253-.62L.85 30.119c-1.146-1.124-1.131-3.205.032-4.312L27.389.812c.703-.579 1.49-.703 2.19-.812zm-3.13 9.935L7.297 27.994l19.153 18.84v-7.342c-.002-1.244.856-2.442 2.034-2.844 14.307-4.882 27.323-1.394 35.145 6.437 3.985 3.989 6.581 9.143 7.355 14.715 2.14-6.959 1.157-13.902-2.441-19.964-5.89-9.92-19.251-17.684-39.089-17.684-1.573 0-3.004-1.429-3.004-3V9.936z"
                                        fill-rule="nonzero" />
                                </svg>
                            </button>
                            @endif
                            <button title="{{ __('Like') }}" class="inline-flex items-center px-1 -ml-1 flex-column">
                                <svg class="w-5 h-5 text-gray-600 cursor-pointer hover:text-gray-700" fill="none"
                                     stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Отображение ответов на комментарий -->
                    <div class="ml-12 mt-2 space-y-2">
                        <!-- Цикл по массиву ответов -->
                        @foreach ($comment['replies'] as $reply)
                            <div class="flex flex-row">
                                <img class="object-cover w-8 h-8 border-2 border-gray-300 rounded-full"
                                     alt="{{ $reply['author'] }}'s avatar"
                                     src="{{ $reply['photo'] }}">
                                <div class="flex-col mt-1">
                                    <div class="flex items-center flex-1 px-2 font-medium leading-tight">
                                        <a href="{{ route('athlete.profile', $reply['userId']) }}">{{ $reply['author'] }}</a>
                                        <span class="ml-2 text-xs font-normal text-gray-500">{{ $reply['date'] }}</span>
                                    </div>
                                    <div class="flex-1 px-2 ml-2 text-sm font-medium leading-loose text-gray-600">
                                        {{ $reply['text'] }}
                                    </div>
                                    <button title="{{ __('Like') }}" class="inline-flex items-center px-1 -ml-1 flex-column">
                                        <svg class="w-5 h-5 text-gray-600 cursor-pointer hover:text-gray-700" fill="none"
                                             stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @if (!$onlyLast)
    <form wire:submit.prevent="addComment" class="mt-4">
        <label>
            <textarea wire:model="commentContent" class="border border-gray-300 rounded-lg p-2 w-full" rows="3" placeholder="Введите комментарий"></textarea>
        </label>
        @error('commentContent')
        <p class="text-red-500 mt-1">{{ $message }}</p>
        @enderror
        <input type="hidden" wire:model="selectedCommentId">
        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded mt-2">Отправить</button>
    </form>
    @endif
</div>
