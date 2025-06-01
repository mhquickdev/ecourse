<!DOCTYPE html>
<html>
<head>
    <title>Certificate of Appreciation</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 0;
            padding: 0;
            /* height: 100vh; Removed viewport height for PDF rendering */
        }
        .certificate-container {
            width: 100%;
            /* min-height: 100%; /* Use min-height again */ */
            background-color: #fff;
            border: 10px solid #0b4f8b; /* Blue border */
            padding: 25px; /* Slightly increase padding for better appearance, still trying to fit */
            box-sizing: border-box;
            position: relative;
            overflow: hidden;
            /* box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); */
            display: flex; /* Use flexbox for main layout */
            flex-direction: column; /* Stack elements vertically */
            justify-content: space-between; /* Distribute space between header, body, and footer content */
        }
        .certificate-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 150px; /* Adjust corner size */
            height: 150px; /* Adjust corner size */
            background-color: #0b4f8b;
            transform: skewX(-25deg) translateX(-50px);
        }
         .certificate-container::after {
            content: '';
            position: absolute;
            bottom: 0;
            right: 0;
            width: 150px; /* Adjust corner size */
            height: 150px; /* Adjust corner size */
            background-color: #0b4f8b;
            transform: skewX(-25deg) translateX(50px);
        }
        .certificate-header {
            text-align: center;
            margin-bottom: 15px; /* Adjust margin */
        }
        .certificate-title {
            font-size: 36px;
            font-weight: bold;
            color: #0b4f8b;
            margin-bottom: 10px;
            text-decoration: underline;
        }
        .certificate-subtitle {
             font-size: 24px;
             color: #e0a800; /* Gold color */
             margin-bottom: 20px;
        }
        .certificate-body {
            text-align: center;
            margin-bottom: 30px; /* Ensure enough space for bottom elements */
            page-break-inside: avoid; /* Hint to avoid breaking this block */
            flex-grow: 1; /* Allow the body to take up available space */
            display: flex; /* Use flexbox for body content layout */
            flex-direction: column; /* Stack body elements vertically */
            justify-content: center; /* Center body content vertically */
        }
        .student-name {
            font-size: 30px;
            font-weight: bold;
            color: #0b4f8b;
            margin-bottom: 20px;
        }
        .recognition-text {
            font-size: 18px;
            color: #333;
            line-height: 1.6;
        }
         .course-details {
             font-size: 18px;
             font-weight: bold;
             color: #e0a800; /* Gold color */
             margin-top: 10px;
         }
         .course-id {
              font-size: 14px;
              color: #666;
              margin-top: 5px;
         }
        .certificate-footer {
            /* Removed footer as elements will be positioned absolutely */
        }
        .signature-block {
            text-align: center;
            /* Removed absolute positioning */
        }
        .signature-line {
            border-bottom: 0px solid #000;
            width: 200px;
            margin: 0 auto 5px auto;
        }
        .signer-name {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }
        .signer-title {
            font-size: 14px;
            color: #666;
        }
         .logo {
             position: absolute;
             top: 40px;
             right: 40px;
             width: 150px; /* Adjust as needed */
         }
         .app-name {
             position: absolute; /* Positioning for site name */
             left: 40px;
             font-size: 20px;
             font-weight: bold;
             color: #0b4f8b;
         }
          .issued-by-label {
              font-size: 14px;
              color: #666;
              margin-bottom: 5px;
          }
          .issue-date {
               text-align: center;
               margin-bottom: 30px; /* Adjust margin */
               font-size: 16px;
               color: #555;
           }
            .verification-text-bottom {
                position: absolute;
                top: 10px; /* Position at the top */
                left: 0;
                right: 0;
                text-align: center;
                font-size: 12px;
                color: #999;
            }
             .verification-text-top {
                 position: absolute;
                 /* Moved to bottom */
             }
             .verification-url {
                 font-size: 12px;
                 color: #0b4f8b;
                 margin-top: 5px;
             }
    </style>
</head>
<body>
    <div class="certificate-container">
        {{-- <img src="{{ asset('images/digixonline_logo.png') }}" alt="DigixOnline Logo" class="logo"> --}}
        {{-- Removed WordPress Logo --}}
        <div class="certificate-header">
            <div class="certificate-title">Certificate of Appreciation</div>
            <div class="certificate-subtitle">This certificate is presented to</div>
        </div>

        <div class="certificate-body">
            <div class="student-name">{{ $user->name ?? 'N/A' }}</div>
            <div class="recognition-text">
                In recognition and sincere appreciation of your successful completion of the course:
            </div>
            <div class="course-details">{{ $course->title ?? 'N/A' }}</div>
            <div class="course-id">(Course ID: {{ $course->id ?? 'N/A' }})</div>
        </div>

        <div class="issue-date">
            Issued on: {{ $certificate->issue_date->format('F j, Y') ?? 'N/A' }}
        </div>

        {{-- Site name and Issued By label at bottom left --}}
        {{-- Removed absolute positioning and integrated into flexbox footer --}}
        {{-- Mentor details at bottom right --}}
        {{-- Removed absolute positioning and integrated into flexbox footer --}}

        {{-- Verification text at the very bottom --}}
        {{-- Removed absolute positioning and integrated into flexbox footer --}}

        <div class="certificate-footer-content">
             {{-- Site name and Issued By label at bottom left --}}
            <div class="app-info-bottom-left" style="text-align: left;"> {{-- Removed absolute positioning --}}
            <div class="issued-by-label">Issued By:</div>
            <div style="font-size: 20px; font-weight: bold; color: #0b4f8b;">{{ config('app.name') }}</div>
        </div>

        {{-- Mentor details at bottom right --}}
        @if($course->mentor)
                 <div class="signature-block" style="text-align: center;"> {{-- Removed absolute positioning --}}
                  {{-- Mentor Signature Image (if available) --}}
                  {{-- <img src="{{ asset('signatures/mentor_signature.png') }}" alt="Mentor Signature" class="signature-img"> --}}
                 <div class="signature-line"></div>
                 <div class="signer-name">{{ $course->mentor->name ?? 'Mentor' }}</div>
                 <div class="signer-title">Mentor</div>
             </div>
         @endif

            {{-- Verification text at the very bottom --}}
            {{-- This might still need careful handling in PDF to stay at the very bottom --}}
            {{-- We might need to adjust the footer structure further if this doesn't work --}}
        </div>

        <div class="verification-text-bottom" style="position: absolute; bottom: 10px; left: 0; right: 0; text-align: center; font-size: 12px; color: #999;"> {{-- Kept absolute positioning for this lowest element --}}
            Verification ID: {{ $certificate->uuid ?? 'N/A' }} | Verify at: {{ route('certificate.verify', $certificate->uuid ?? 'N/A') }}
        </div>

        <div class="verification-text-top" style="position: absolute; top: 10px; left: 0; right: 0; text-align: center; font-size: 12px; color: #999;">
            Verification ID: {{ $certificate->uuid ?? 'N/A' }} | Verify at: {{ route('certificate.verify', $certificate->uuid ?? 'N/A') }}
        </div>

    </div>
</body>
</html> 