<div class="mb-6" data-content-id="{{ $content->id }}">
    <div class="flex justify-between items-center mb-3">
        <h5 class="font-bold text-gray-800">{{ $content->title }}</h5>
        @if(!$isCompleted)
            <button class="mark-completed-btn bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors duration-200">
                Mark as Completed
            </button>
        @else
            <span class="text-green-600 font-semibold">
                <i class="fas fa-check-circle"></i> Completed
            </span>
        @endif
    </div>
    
    @if($content->type === 'video')
        @php
            $videoUrls = json_decode($content->video_urls, true);
            $videoUrl = $videoUrls[0] ?? null;
            $videoFiles = json_decode($content->video_files, true);
            $videoFile = $videoFiles[0] ?? null;
            $isYouTube = false;
            $isHosted = false;
            $videoId = null;

            if ($videoUrl) {
                if (Str::contains($videoUrl, 'youtube.com') || Str::contains($videoUrl, 'youtu.be')) {
                    $isYouTube = true;
                    $query = parse_url($videoUrl, PHP_URL_QUERY);
                    if ($query) {
                        parse_str($query, $params);
                        if (isset($params['v'])) {
                            $videoId = $params['v'];
                        } else if (isset($params['list'])) {
                            if (isset($params['v'])) {
                                $videoId = $params['v'];
                            }
                        }
                    }
                    if (!$videoId) {
                        $path = parse_url($videoUrl, PHP_URL_PATH);
                        if ($path) {
                            $videoId = ltrim($path, '/');
                        }
                    }
                }
            }

            if (!$isYouTube && $videoFile) {
                $isHosted = true;
            }
        @endphp

        <div class="video-container bg-gray-900 rounded-lg shadow-xl overflow-hidden relative" style="padding-top: 56.25%;">
             @if($isYouTube && $videoId)
                <iframe id="youtube-player" class="absolute inset-0 w-full h-full" 
                        src="https://www.youtube.com/embed/{{ $videoId }}?enablejsapi=1&modestbranding=1&rel=0&showinfo=0" 
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen></iframe>
            @elseif($isHosted)
                <video id="hosted-video" controls controlslist="nodownload" class="absolute inset-0 w-full h-full object-cover">
                    <source src="{{ asset('storage/' . $videoFile) }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            @else
                 <div class="absolute inset-0 flex items-center justify-center text-red-600 bg-gray-100">
                     No valid video source available for this content.
                 </div>
            @endif
        </div>

        @push('styles')
        <style>
            /* Attempt to hide download button in some browsers */
            video::-webkit-media-controls-timeline,
            video::-webkit-media-controls-current-time-display,
            video::-webkit-media-controls-time-remaining-display,
            video::-webkit-media-controls-fullscreen-button {}
            
            video::-webkit-media-controls-enclosure {
                overflow:hidden;
            }
            video::-webkit-media-controls-container {
                 overflow: hidden;
            }
            video::-webkit-media-controls-panel {
                width: calc(100% + 30px); /* Add a little more width to hide button */
            }
            video::-webkit-media-controls-download-button {
                display: none;
            }

             /* Firefox */
            video::--moz-context-menu:hover .media-button[download] {
                 display: none;
            }

            /* Standard way (may not be widely supported for specific buttons) */
            video::cue {
                /* Styles for cues, not controls */
            }

             /* For controlslist="nodownload" */
             video::-webkit-media-controls-elements {
                display: flex !important;
             }

        </style>
        @endpush
    @elseif($content->type === 'file')
        <div>
            <h6 class="font-semibold text-gray-800 mb-2">Files</h6>
            @if(!empty($content->file_urls))
                @foreach(json_decode($content->file_urls, true) as $url)
                    <a href="{{ $url }}" target="_blank" class="text-blue-600 underline block mb-1">{{ $url }}</a>
                @endforeach
            @endif
            @if(!empty($content->file_files))
                @foreach(json_decode($content->file_files, true) as $file)
                    <a href="{{ asset('storage/' . $file) }}" target="_blank" class="text-blue-600 underline block mb-1 px-3 py-1 rounded-lg bg-blue-500 text-white hover:bg-blue-600 transition-colors duration-200">Download File</a>
                @endforeach
            @endif
        </div>
    @elseif($content->type === 'quiz')
        <div id="quiz-content">
            <h6 class="font-semibold text-gray-800 mb-2">Quiz: {{ $content->quiz_question }}</h6>
            @if(!empty($content->quiz_options))
                <p class="font-semibold text-gray-800 mb-1">Options:</p>
                <div class="space-y-2">
                    @foreach(json_decode($content->quiz_options, true) as $key => $option)
                        @php
                            $isCorrectAnswer = $isCompleted && ($option === $content->quiz_answer);
                        @endphp
                        <label class="flex items-center p-3 rounded-lg border cursor-pointer {{ $isCorrectAnswer ? 'bg-green-100 border-green-500' : 'border-gray-200 hover:bg-gray-50' }}">
                            <input type="radio" name="quiz_option" value="{{ $option }}" class="form-radio text-blue-600" {{ $isCompleted ? 'disabled' : '' }} {{ $isCorrectAnswer ? 'checked' : '' }}>
                            <span class="ml-2 text-gray-700 {{ $isCorrectAnswer ? 'font-bold text-green-800' : '' }}">{{ $option }}</span>
                             @if($isCorrectAnswer)
                                <i class="fas fa-check-circle text-green-600 ml-auto"></i>
                            @endif
                        </label>
                    @endforeach
                </div>
            @endif

            @if($isCompleted)
                <div id="quiz-result" class="mt-3 font-semibold"></div>
            @else
                <button id="submit-quiz" class="mt-4 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors duration-200">Submit Answer</button>
                <div id="quiz-result" class="mt-3 font-semibold"></div>
            @endif
        </div>
    @endif

    @if(!empty($content->resources))
        <div class="mt-4">
            <h6 class="font-semibold text-gray-800 mb-2">Resources</h6>
            @foreach(json_decode($content->resources, true) as $resource)
                @if(isset($resource['type']) && $resource['type'] === 'url' && isset($resource['url']))
                    <a href="{{ $resource['url'] }}" target="_blank" class="text-blue-600 underline block mb-1">{{ $resource['url'] }}</a>
                @elseif(isset($resource['type']) && $resource['type'] === 'file' && isset($resource['file']))
                    <a href="{{ asset('storage/' . $resource['file']) }}" target="_blank" class="text-blue-600 underline block mb-1">Download Resource</a>
                @endif
            @endforeach
        </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const contentId = {{ $content->id }};
    const markCompletedBtn = document.querySelector('.mark-completed-btn');
    
    if (markCompletedBtn) {
        markCompletedBtn.addEventListener('click', function() {
            fetch(`/student/content/${contentId}/mark-completed`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    markCompletedBtn.replaceWith(`
                        <span class="text-green-600 font-semibold">
                            <i class="fas fa-check-circle"></i> Completed
                        </span>
                    `);
                }
            });
        });
    }

    // Video completion tracking
    const youtubePlayer = document.getElementById('youtube-player');
    const hostedVideo = document.getElementById('hosted-video');

    if (youtubePlayer) {
        // Load YouTube API
        const tag = document.createElement('script');
        tag.src = "https://www.youtube.com/iframe_api";
        const firstScriptTag = document.getElementsByTagName('script')[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

        let player;
        window.onYouTubeIframeAPIReady = function() {
            player = new YT.Player('youtube-player', {
                events: {
                    'onStateChange': onPlayerStateChange
                }
            });
        };

        function onPlayerStateChange(event) {
            if (event.data === YT.PlayerState.ENDED) {
                markContentAsCompleted();
            }
        }
    }

    if (hostedVideo) {
        hostedVideo.addEventListener('ended', function() {
            markContentAsCompleted();
        });
    }

    function markContentAsCompleted() {
        if (!document.querySelector('.text-green-600')) {
            fetch(`/student/content/${contentId}/mark-completed`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const markCompletedBtn = document.querySelector('.mark-completed-btn');
                    if (markCompletedBtn) {
                        markCompletedBtn.replaceWith(`
                            <span class="text-green-600 font-semibold">
                                <i class="fas fa-check-circle"></i> Completed
                            </span>
                        `);
                    }
                }
            });
        }
    }
});
</script>
@endpush 