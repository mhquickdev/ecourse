<!DOCTYPE html>
<html>
<head>
    <title>Certificate Verification</title>
    <style>
        body {
            font-family: sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f7f7f7;
            color: #333;
        }
        .verification-container {
            background-color: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 600px;
            width: 100%;
        }
        .verification-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #0b4f8b;
        }
        .certificate-details p {
            font-size: 16px;
            margin-bottom: 10px;
        }
        .certificate-details strong {
            color: #0b4f8b;
        }
         .download-link {
             display: inline-block;
             margin-top: 20px;
             padding: 10px 20px;
             background-color: #0b4f8b;
             color: #fff;
             text-decoration: none;
             border-radius: 5px;
             transition: background-color 0.3s ease;
         }
         .download-link:hover {
             background-color: #083f6a;
         }
          .error-message {
              font-size: 18px;
              color: #d9534f;
              margin-bottom: 20px;
          }
    </style>
</head>
<body>
    <div class="verification-container">
        @if(isset($certificate))
            <div class="verification-title">Certificate Verified</div>
            <div class="certificate-details">
                <p><strong>Recipient Name:</strong> {{ $certificate->user->name ?? 'N/A' }}</p>
                <p><strong>Course Title:</strong> {{ $certificate->course->title ?? 'N/A' }}</p>
                <p><strong>Issued On:</strong> {{ $certificate->issue_date->format('F j, Y') }}</p>
                <p><strong>Certificate ID:</strong> {{ $certificate->uuid }}</p>
                {{-- Add more details if needed --}}
            </div>
            <a href="{{ Storage::url($certificate->file_path) }}" class="download-link" download>Download Certificate</a>
        @else
            <div class="error-message">Invalid Certificate ID or Certificate not found.</div>
        @endif
    </div>
</body>
</html> 