<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Shubham Das - Salesforce Consultant & Project Lead</title>
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #8da4b4;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            padding: 40px 20px;
            color: #0f172a;
        }

        /* Main Card Container */
        .card {
            background-color: #dbeeff;
            width: 100%;
            max-width: 640px;
            border-radius: 28px;
            padding: 32px 28px 40px 28px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Profile Header Info */
        .profile-name {
            font-size: 3rem;
            font-weight: 700;
            color: #000000;
            text-align: center;
            letter-spacing: -1px;
            line-height: 0.98;
            z-index: 1;
            margin-top: 4px;
        }

        /* Profile Portrait Cutout */
        .portrait-wrapper {
            position: relative;
            width: 290px;
            margin-top: -90px;
            margin-bottom: 0px;
            display: flex;
            justify-content: center;
            align-items: flex-end;
            z-index: 2;
        }

        .portrait-img {
            width: 100%;
            height: auto;
            /* max-height: 340px; */
            object-fit: contain;
            display: block;
            filter: contrast(1.03) brightness(1.02);
        }

        .portrait-fade {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 100px;
            background: linear-gradient(to bottom, rgba(219, 238, 255, 0) 0%, rgba(219, 238, 255, 0.95) 75%, rgba(219, 238, 255, 1) 100%);
            pointer-events: none;
        }

        .profile-bio {
            font-size: 15px;
            line-height: 1.5;
            color: #1e293b;
            text-align: center;
            max-width: 520px;
            font-weight: 500;
            margin-bottom: 24px;
        }

        /* Social Icons Row */
        .social-row {
            display: flex;
            gap: 20px;
            margin-bottom: 32px;
            align-items: center;
        }

        .social-icon {
            color: #000;
            transition: transform 0.2s ease, opacity 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }

        .social-icon:hover {
            transform: translateY(-2px);
            opacity: 0.8;
        }

        /* Preview Card Frame (Tablet/Screen Mockup) */
        .mockup-frame {
            width: 100%;
            background: #ffffff;
            border-radius: 20px;
            border: 2px solid #0f172a;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .frame-content {
            width: 100%;
            background: #ffffff;
            display: flex;
            flex-direction: column;
        }

        /* Mockup Footer Caption Line */
        .mockup-footer-bar {
            padding: 14px 20px;
            background: #ffffff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #f1f5f9;
        }

        .footer-text {
            font-size: 14px;
            font-weight: 600;
            color: #0f172a;
        }

        .more-options {
            color: #64748b;
            cursor: pointer;
        }
    </style>
</head>

<body>

    <div class="card">
        <!-- Name Header -->
        <h1 class="profile-name">Shubham Das</h1>

        <!-- Profile Cutout Portrait -->
        <div class="portrait-wrapper">
            <img src="profile_cutout.png" alt="Shubham Das" class="portrait-img" id="avatar-img" />
            <div class="portrait-fade"></div>
        </div>
        <p class="profile-bio">
            Salesforce Consultant & Project Lead | 10x Certified |<br> Sales, Service, Marketing & Data Cloud + Agentforce |<br> Apex • LWC | HubSpot | Helping businesses turn CRM into revenue
        </p>

        <!-- Social Media Icons Row -->
        <div class="social-row">
            <!-- WhatsApp Icon -->
            <a href="https://wa.me/+919583162067" class="social-icon" title="WhatsApp" target="_blank">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.81 9.81 0 0 0 12.04 2zm.01 1.67c4.55 0 8.24 3.69 8.24 8.24 0 2.2-.86 4.27-2.42 5.82a8.19 8.19 0 0 1-5.82 2.42c-1.46 0-2.89-.39-4.14-1.13l-.3-.18-3.08.81.82-3-.19-.31c-.81-1.29-1.24-2.77-1.24-4.31 0-4.55 3.69-8.24 8.24-8.24zm4.52 10.3c-.25-.13-1.47-.72-1.7-.81-.23-.08-.39-.13-.56.13-.17.25-.66.81-.81.98-.15.17-.3.19-.55.06-.25-.13-1.06-.39-2.02-1.25-.75-.67-1.25-1.49-1.4-1.74-.15-.25-.02-.38.11-.5.11-.11.25-.29.37-.44.13-.15.17-.25.25-.42.08-.17.04-.32-.02-.45s-.56-1.36-.77-1.86c-.2-.49-.4-.42-.55-.43l-.47-.01c-.17 0-.44.06-.67.31s-.88.86-.88 2.1.9 2.44 1.03 2.61c.13.17 1.77 2.7 4.29 3.79.6.26 1.07.41 1.43.53.6.19 1.15.16 1.58.1.48-.07 1.47-.6 1.68-1.18.21-.58.21-1.07.15-1.18-.06-.11-.22-.18-.47-.31z" />
                </svg>
            </a>

            <!-- LinkedIn Icon -->
            <a href="https://www.linkedin.com/in/shubhamdas28/" target="_blank" class="social-icon" title="LinkedIn">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.28 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.75M6.46 10.9v8.37H9.25V10.9H6.46M7.86 6.6a1.49 1.49 0 0 0-1.49 1.49c0 .82.67 1.49 1.49 1.49a1.49 1.49 0 0 0 1.49-1.49c0-.82-.67-1.49-1.49-1.49z" />
                </svg>
            </a>

            <!-- Slack Icon -->
            <a href="https://cccinfotech.slack.com/team/U06BBKN6S73" target="_blank" class="social-icon" title="Slack">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M5.042 15.165a2.528 2.528 0 0 1-2.52 2.523A2.528 2.528 0 0 1 0 15.165a2.527 2.527 0 0 1 2.522-2.52h2.52v2.52zM6.313 15.165a2.527 2.527 0 0 1 2.521-2.52 2.527 2.527 0 0 1 2.521 2.52v6.313A2.528 2.528 0 0 1 8.834 24a2.528 2.528 0 0 1-2.521-2.522v-6.313zM8.834 5.042a2.528 2.528 0 0 1-2.521-2.52A2.528 2.528 0 0 1 8.834 0a2.528 2.528 0 0 1 2.521 2.522v2.52H8.834zM8.834 6.313a2.528 2.528 0 0 1 2.521 2.521 2.528 2.528 0 0 1-2.521 2.521H2.522A2.528 2.528 0 0 1 0 8.834a2.528 2.528 0 0 1 2.522-2.521h6.312zM18.956 8.834a2.528 2.528 0 0 1 2.522-2.521A2.528 2.528 0 0 1 24 8.834a2.528 2.528 0 0 1-2.522 2.521h-2.522V8.834zM17.688 8.834a2.528 2.528 0 0 1-2.523 2.521 2.527 2.527 0 0 1-2.52-2.521V2.522A2.527 2.527 0 0 1 15.165 0a2.528 2.528 0 0 1 2.523 2.522v6.312zM15.165 18.956a2.528 2.528 0 0 1 2.523 2.52A2.528 2.528 0 0 1 15.165 24a2.527 2.527 0 0 1-2.52-2.522v-2.522h2.52zM15.165 17.688a2.527 2.527 0 0 1-2.52-2.523 2.527 2.527 0 0 1 2.52-2.52h6.323A2.528 2.528 0 0 1 24 15.165a2.528 2.528 0 0 1-2.522 2.523h-6.313z" />
                </svg>
            </a>

            <!-- Email Icon -->
            <a href="mailTo:shubham@cccinfotech.com" class="social-icon" title="Email">
                <svg width="30" height="30" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" />
                </svg>
            </a>
        </div>

        <!-- Embedded Tablet Mockup Screen -->
        <div class="mockup-frame">
            <div class="frame-content" style="height: 380px; overflow: hidden; position: relative;">
                <iframe src="http://cccinfotech.com/" style="width: 100%; height: 800px; border: none; display: block; pointer-events: none;" scrolling="no"></iframe>
            </div>
            <!-- Bottom Caption -->
            <div class="mockup-footer-bar">
                <div class="footer-text">Leading Summit Salesforce Partner in the India - CloudCentric</div>
                <div class="more-options">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Use proper photo if available
        const img = document.getElementById('avatar-img');
        img.onerror = function() {
            // Fallback SVG avatar if blocked
            this.src = 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 24 24" fill="%23475569"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 4c1.93 0 3.5 1.57 3.5 3.5S13.93 13 12 13s-3.5-1.57-3.5-3.5S10.07 6 12 6zm0 14c-2.03 0-4.43-.82-6.14-2.88C7.55 15.8 9.68 15 12 15s4.45.8 6.14 2.12C16.43 19.18 14.03 20 12 20z"/></svg>';
        }
    </script>
</body>

</html>