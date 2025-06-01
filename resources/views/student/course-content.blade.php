@extends('layouts.student')

@section('content')
<div class="p-8">
    <div class="max-w-full mx-auto">
        <div class="flex flex-col md:flex-row gap-8">
            <!-- Sidebar with modules -->
            <div class="md:w-1/4">
                <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
                    <h5 class="text-xl font-bold text-gray-900 mb-4">Course Modules</h5>
                    <div class="space-y-4">
                        @foreach($course->modules as $module)
                            <div class="border rounded-lg overflow-hidden">
                                <button type="button" class="w-full flex justify-between items-center px-4 py-3 bg-gray-50 hover:bg-gray-100 font-semibold text-left focus:outline-none" onclick="document.getElementById('module-content-{{ $module->id }}').classList.toggle('hidden')">
                                    <span>{{ $module->title }}</span>
                                    <i class="fa fa-chevron-down ml-2"></i>
                                </button>
                                <div id="module-content-{{ $module->id }}" class="hidden bg-white">
                                    @forelse($module->contents as $content)
                                        @php
                                            $isCompleted = $completions[$content->id] ?? false;
                                        @endphp
                                        <a href="#" class="flex items-center justify-between px-6 py-3 border-t hover:bg-gray-50 transition content-link" data-content-id="{{ $content->id }}">
                                            <div class="flex items-center gap-2">
                                                @if($content->type === 'video')
                                                    <i class="fa-solid fa-play-circle {{ $isCompleted ? 'text-green-500' : 'text-pink-500' }}"></i>
                                                @elseif($content->type === 'quiz')
                                                    <i class="fa-solid fa-question-circle {{ $isCompleted ? 'text-green-500' : 'text-yellow-500' }}"></i>
                                                @elseif($content->type === 'file')
                                                    <i class="fa-solid fa-file-alt {{ $isCompleted ? 'text-green-500' : 'text-green-500' }}"></i>
                                                @else
                                                    <i class="fa-solid fa-circle {{ $isCompleted ? 'text-green-500' : 'text-gray-400' }}"></i>
                                                @endif
                                                <span class="font-medium text-gray-800">{{ $content->title }}</span>
                                            </div>
                                            @if($isCompleted)
                                                 <span class="text-green-600"><i class="fas fa-check-circle"></i></span>
                                            @endif
                                        </a>
                                    @empty
                                        <div class="px-6 py-3 text-gray-400">No content</div>
                                    @endforelse
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Main content area -->
            <div class="md:w-3/4">
                <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
                    <div id="course-content-area">
                         @if(isset($firstContent))
                            @php
                                $isCompleted = $completions[$firstContent->id] ?? false;
                            @endphp
                             @include('student.partials.course-content-item', ['content' => $firstContent, 'isCompleted' => $isCompleted])
                        @endif
                    </div>

                    <div class="flex justify-between mt-8">
                        <button id="prev-content" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg">Previous</button>
                        <button id="next-content" class="bg-blue-600 text-white px-4 py-2 rounded-lg">Next</button>
                    </div>

                    <hr class="my-6">

                    <div class="course-progress mt-6">
                        <h5 class="font-bold text-gray-800 mb-3">Your Progress</h5>
                        <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $enrollment->progress_percentage ?? 0 }}%"></div>
                        </div>
                        <div class="text-right text-sm font-medium text-gray-600 mt-1"><span id="progress-percentage">{{ $enrollment->progress_percentage ?? 0 }}</span>% Complete</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const contentLinks = document.querySelectorAll('.content-link');
        const contentArea = document.getElementById('course-content-area');
        const prevButton = document.getElementById('prev-content');
        const nextButton = document.getElementById('next-content');
        const progressPercentageSpan = document.getElementById('progress-percentage');
        let currentContentId = {{ $firstContent->id ?? 'null' }};

        // Map of content IDs and their order
        const contentOrder = [];
        contentLinks.forEach(link => {
            contentOrder.push(parseInt(link.dataset.contentId));
        });

        // Function to load content via AJAX
        function loadContent(contentId) {
            console.log('Loading content with ID:', contentId);
             fetch(`/student/courses/{{ $course->id }}/content/${contentId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.html) {
                        contentArea.innerHTML = data.html;
                        currentContentId = contentId;
                        updateNavigationButtons();
                        attachContentEventListeners(); // Attach listeners after loading new content
                        updateSidebarSelection(contentId); // Update sidebar selection
                    }
                })
                .catch(error => console.error('Error loading content:', error));
        }

        // Function to update sidebar selection and expand module
        function updateSidebarSelection(contentId) {
            // Remove active class from all links
            contentLinks.forEach(link => {
                link.classList.remove('font-bold', 'bg-blue-100', 'text-blue-800');
            });

            // Find and highlight the current link
            const currentLink = document.querySelector(`.content-link[data-content-id='${contentId}']`);
            if (currentLink) {
                currentLink.classList.add('font-bold', 'bg-blue-100', 'text-blue-800');

                // Find the parent module and expand it
                const parentModuleContent = currentLink.closest('.border').querySelector('.bg-white');
                if (parentModuleContent) {
                    parentModuleContent.classList.remove('hidden');
                     // Optional: Rotate the chevron icon
                    const chevronIcon = currentLink.closest('.border').querySelector('.fa-chevron-down');
                     if (chevronIcon) {
                         chevronIcon.classList.add('rotate-180');
                     }
                }
            }
        }

        // Function to attach event listeners to the currently displayed content
        function attachContentEventListeners() {
            const currentContentElement = contentArea.querySelector(`[data-content-id="${currentContentId}"]`);

            // Mark as Completed button listener
            const markCompletedBtn = currentContentElement.querySelector('.mark-completed-btn');
            if (markCompletedBtn) {
                markCompletedBtn.addEventListener('click', function() {
                    markContentAsCompleted(currentContentId);
                });
            }

            // Quiz submission listener
            const submitQuizButton = currentContentElement.querySelector('#submit-quiz');
            const quizResultDiv = currentContentElement.querySelector('#quiz-result');

            if (submitQuizButton && quizResultDiv) {
                submitQuizButton.addEventListener('click', function() {
                    const selectedOption = currentContentElement.querySelector('input[name="quiz_option"]:checked');
                    
                    if (selectedOption) {
                        const answer = selectedOption.value;
                        fetch(`/student/content/${currentContentId}/submit-quiz`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token
                            },
                            body: JSON.stringify({ answer: answer })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Update the UI based on success (handled by markContentAsCompleted call)
                                markContentAsCompleted(currentContentId, true); // Pass true to indicate it was quiz completion
                            } else {
                                quizResultDiv.innerHTML = '<span class="text-red-600">' + data.message + '</span>';
                            }
                        })
                        .catch(error => console.error('Error submitting quiz:', error));
                    } else {
                        quizResultDiv.innerHTML = '<span class="text-yellow-600">Please select an answer.</span>';
                    }
                });
            }

            // Video completion tracking
            const youtubePlayerElement = currentContentElement.querySelector('#youtube-player');
            const hostedVideoElement = currentContentElement.querySelector('#hosted-video');

            if (youtubePlayerElement) {
                // Load YouTube API script if not already loaded
                if (!window.YT) {
                    const tag = document.createElement('script');
                    tag.src = "https://www.youtube.com/iframe_api";
                    const firstScriptTag = document.getElementsByTagName('script')[0];
                    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
                } else if (window.YT && window.YT.Player) {
                    // If API is already ready, create player directly
                    createYouTubePlayer(youtubePlayerElement.id, currentContentId);
                }

                // Define the onYouTubeIframeAPIReady globally if it doesn't exist
                if (!window.onYouTubeIframeAPIReady) {
                    window.onYouTubeIframeAPIReady = function() {
                        // Loop through all potential youtube players on the page and create them
                        // This handles initial load and potential subsequent players if structure changes
                        document.querySelectorAll('iframe[id^="youtube-player"]').forEach(playerElement => {
                            // Ensure player is not already created for this element
                            if (!playerElement.dataset.playerCreated) {
                                const contentId = parseInt(playerElement.closest('[data-content-id]').dataset.contentId);
                                createYouTubePlayer(playerElement.id, contentId);
                            }
                        });
                    };
                }
            }

            if (hostedVideoElement) {
                hostedVideoElement.addEventListener('ended', function() {
                    // Check if the current content is still the one being watched
                    if (currentContentId === parseInt(currentContentElement.dataset.contentId)) {
                        markContentAsCompleted(currentContentId);
                    }
                });
            }
        }

        // Helper function to create YouTube Player and attach listener
        function createYouTubePlayer(elementId, contentId) {
            new YT.Player(elementId, {
                events: {
                    'onStateChange': function(event) {
                        // Check if the current content is still the one being watched
                        if (currentContentId === contentId) {
                            if (event.data === YT.PlayerState.ENDED) {
                                markContentAsCompleted(contentId);
                            }
                        }
                    }
                }
            });
            // Mark the element to prevent creating player multiple times
            document.getElementById(elementId).dataset.playerCreated = 'true';
        }

        function markContentAsCompleted(contentId, isQuiz = false) {
            const currentContentElement = contentArea.querySelector(`[data-content-id="${contentId}"]`);
            // Prevent marking if already marked (check UI indicator at the top)
            if (currentContentElement && !currentContentElement.querySelector('.content-completed-indicator')) {
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
                        // Update the UI to show completed status at the top
                        const titleContainer = currentContentElement.querySelector('.flex.justify-between.items-center.mb-3');
                        const markCompletedBtn = titleContainer.querySelector('.mark-completed-btn');
                        if (markCompletedBtn) {
                            markCompletedBtn.remove(); // Remove the button
                        }
                         // Add the completed indicator span
                        const completedSpan = document.createElement('span');
                        completedSpan.classList.add('text-green-600', 'font-semibold', 'content-completed-indicator');
                        completedSpan.innerHTML = '<i class="fas fa-check-circle"></i> Completed';
                        titleContainer.appendChild(completedSpan);

                        // If it was a quiz, the partial view logic will handle showing the answer
                        // and disabling options/button on the next loadContent call if needed,
                        // but the submitQuiz fetch already handled disabling and message.
                        // We might need to trigger a UI update for the quiz specifically
                        // or rely on the next navigation event loading the updated partial.
                        if (isQuiz) {
                            // Reload the content to show the completed quiz state with answer
                            loadContent(contentId);
                        }

                        // Update the sidebar to show completion status
                        updateSidebarCompletion(contentId, true);

                        // Update progress bar visually
                        updateProgressBar();

                        console.log('Content marked as completed', contentId);
                    }
                })
                .catch(error => console.error('Error marking content as completed:', error));
            }
        }

        // Function to update button states (enable/disable)
        function updateNavigationButtons() {
            const currentContentIndex = contentOrder.indexOf(currentContentId);
            prevButton.disabled = currentContentIndex <= 0;
            nextButton.disabled = currentContentIndex >= contentOrder.length - 1;

            // Update button styles based on disabled state (using Tailwind classes)
            if (prevButton.disabled) {
                prevButton.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                prevButton.classList.add('bg-gray-300', 'text-gray-700', 'cursor-not-allowed');
            } else {
                prevButton.classList.remove('bg-gray-300', 'text-gray-700', 'cursor-not-allowed');
                prevButton.classList.add('bg-blue-600', 'hover:bg-blue-700', 'text-white');
            }

            if (nextButton.disabled) {
                nextButton.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                nextButton.classList.add('bg-gray-300', 'text-gray-700', 'cursor-not-allowed');
            } else {
                nextButton.classList.remove('bg-gray-300', 'text-gray-700', 'cursor-not-allowed');
                nextButton.classList.add('bg-blue-600', 'hover:bg-blue-700', 'text-white');
            }
        }

        // Function to update completion status in the sidebar
        function updateSidebarCompletion(contentId, isCompleted) {
            const sidebarLink = document.querySelector(`.content-link[data-content-id='${contentId}']`);
            if (sidebarLink) {
                // Remove existing completion indicator if any
                sidebarLink.querySelectorAll('.fa-check-circle, .text-green-600.float-end').forEach(el => el.remove());

                if (isCompleted) {
                    // Add completed indicator
                    const completedSpan = document.createElement('span');
                    completedSpan.classList.add('text-green-600', 'float-end'); // Use float-end or Tailwind equivalent for right alignment
                    completedSpan.innerHTML = '<i class="fas fa-check-circle"></i>'; // Assuming Font Awesome is used
                    sidebarLink.appendChild(completedSpan);

                    // Optional: Update the icon color in the sidebar
                    const icon = sidebarLink.querySelector('i:not(.fa-check-circle)');
                    if (icon) {
                        // Remove existing color classes and add green
                        icon.classList.remove('text-pink-500', 'text-yellow-500', 'text-green-500', 'text-gray-400');
                        icon.classList.add('text-green-500');
                    }
                }
            }
        }

        // Function to update the progress bar percentage
        function updateProgressBar() {
            // Fetch the updated enrollment to get the new progress percentage
            fetch('/student/enrollment/{{ $enrollment->id }}') // Assuming a route to get enrollment by ID
                .then(response => response.json())
                .then(data => {
                    if (data.enrollment) {
                        const progress = data.enrollment.progress_percentage;
                        document.querySelector('.course-progress .bg-blue-600').style.width = progress + '%';
                        progressPercentageSpan.textContent = progress;
                    }
                })
                .catch(error => console.error('Error updating progress bar:', error));
        }

        // Event listeners for sidebar links
        contentLinks.forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                const contentId = parseInt(this.dataset.contentId);
                loadContent(contentId);

                // Highlighting and expansion handled by updateSidebarSelection
                // contentLinks.forEach(l => l.classList.remove('font-bold'));
                // this.classList.add('font-bold');
            });
        });

        // Event listeners for navigation buttons
        nextButton.addEventListener('click', function () {
            let currentContentIndex = contentOrder.indexOf(currentContentId);
            if (currentContentIndex < contentOrder.length - 1) {
                const nextContentId = contentOrder[currentContentIndex + 1];
                loadContent(nextContentId);

                // Optional: Highlight the next link in the sidebar
                contentLinks.forEach(l => l.classList.remove('font-bold'));
                contentLinks[currentContentIndex + 1].classList.add('font-bold');
            }
        });

        prevButton.addEventListener('click', function () {
            let currentContentIndex = contentOrder.indexOf(currentContentId);
            if (currentContentIndex > 0) {
                const prevContentId = contentOrder[currentContentIndex - 1];
                loadContent(prevContentId);

                // Optional: Highlight the previous link in the sidebar
                contentLinks.forEach(l => l.classList.remove('font-bold'));
                contentLinks[currentContentIndex - 1].classList.add('font-bold');
            }
        });

        // Initial load: Highlight the first content link and load its content
        if (currentContentId !== null) {
            const firstContentLink = document.querySelector(`.content-link[data-content-id='${currentContentId}']`);
            if(firstContentLink) {
                firstContentLink.click(); // Trigger click to load content and attach listeners
            }
        } else if (contentLinks.length > 0) {
            // If no first content is set but there are links, load the first one
            contentLinks[0].click();
        }

        // Manually trigger the YouTube API ready function if the script was already loaded
        if (window.YT && window.YT.Player && !window.onYouTubeIframeAPIReady) {
            window.onYouTubeIframeAPIReady = function() {
                document.querySelectorAll('iframe[id^="youtube-player"]').forEach(playerElement => {
                    if (!playerElement.dataset.playerCreated) {
                        const contentId = parseInt(playerElement.closest('[data-content-id]').dataset.contentId);
                        createYouTubePlayer(playerElement.id, contentId);
                    }
                });
            };
            // Call it manually if the DOM is already complete
            if (document.readyState === 'complete') {
                window.onYouTubeIframeAPIReady();
            }
        }
    });
</script>
@endpush
@endsection 