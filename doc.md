Course Edit Page Implementation Plan
This document outlines the phased approach to building the new course edit page, based on the requirements and reference designs.
Phase 1: Display Course Data (Read-Only for Content)
Editable Fields:
Course Title
Description
Price
Preview Image
Category
Tags
Read-Only (Non-Editable) Fields:
All existing module and content details (including quiz, file, resources, etc.)
Removable/Appendable:
User can remove any module, content, or demo video
User can add new modules, contents, or demo videos
UI:
Display all modules and their contents as per their type (video, quiz, file, etc.)
Show demo videos
Use a layout similar to the reference, but include extra fields (quiz, files, resources) as needed
Phase 2: Add/Remove Functionality
Add:
Allow adding new modules, contents, and demo videos (with full editing for new items)
Remove:
Allow removing any module, content, or demo video
Restriction:
Existing modules/contents/demo videos cannot be edited, only removed
Phase 3: Form Submission & Validation
Backend:
Update controller to handle the new structure:
Update only editable fields
Remove modules/contents/demo videos as requested
Add new modules/contents/demo videos
Frontend:
Ensure form submission matches backend expectations
Show validation errors and upload progress
Phase 4: UI/UX Enhancements
Improve user experience:
Collapsible modules/contents
Confirmation dialogs for removals
Progress indicators for uploads
Consistent styling with the rest of the app
Phase 5: Testing & Polish
Test all scenarios:
Editing course info
Adding/removing modules/contents/demo videos
Submitting the form
Handling validation and errors
Polish UI:
Final tweaks based on feedback