<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Shubham Das - Salesforce Consultant & Project Lead</title>
    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-primary: #F3F4F4;
            --bg-card: #ffffff;
            --border-color: #e2e8f0;
            --text-primary: #0f172a;
            --text-secondary: #475569;
            --text-muted: #64748b;
            --accent-blue: #00A0E3;
            --accent-blue-hover: #006ecf;
            --accent-blue-light: #e0f2fe;
            --card-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05), 0 2px 8px -1px rgba(0, 0, 0, 0.03);
            --transition-speed: 0.25s;
            --btn-bg-secondary: #f1f5f9;
            --btn-text-secondary: #0f172a;
            --badge-bg: #ffffff;
            --services-sidebar-bg: #f1f5f9;
        }

        [data-theme="dark"] {
            --bg-primary: #0b0f19;
            --bg-card: #131926;
            --border-color: #1e293b;
            --text-primary: #f3f4f6;
            --text-secondary: #9ca3af;
            --text-muted: #6b7280;
            --accent-blue: #38bdf8;
            --accent-blue-hover: #0ea5e9;
            --accent-blue-light: #0369a1;
            --card-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.5);
            --btn-bg-secondary: #1f2937;
            --btn-text-secondary: #f3f4f6;
            --badge-bg: #1e293b;
            --services-sidebar-bg: #0b0f19;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
            scroll-padding-top: 100px;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-primary);
            color: var(--text-primary);
            transition: background-color 0.3s, color 0.3s;
            line-height: 1.5;
        }

        /* Layout */
        .app-layout {
            display: flex;
            max-width: 1240px;
            margin: 0 auto;
            gap: 50px;
            padding: 40px 20px;
        }

        /* Sidebar Column */
        .sidebar-column {
            width: 420px;
            flex-shrink: 0;
            position: sticky;
            top: 40px;
            height: fit-content;
        }

        .profile-card {
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 28px;
            padding: 24px;
            box-shadow: var(--card-shadow);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            transition: background-color 0.3s, border-color 0.3s;
        }

        .sidebar-header {
            display: flex;
            justify-content: space-between;
            width: 100%;
            margin-bottom: 20px;
        }

        .icon-btn {
            background: none;
            border: none;
            cursor: pointer;
            color: var(--text-primary);
            padding: 8px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background var(--transition-speed), color var(--transition-speed);
        }

        .icon-btn:hover {
            background: var(--btn-bg-secondary);
        }

        .profile-img-container {
            width: 100%;
            border-radius: 24px;
            overflow: hidden;
            margin-bottom: 20px;
            aspect-ratio: 1 / 1.05;
        }

        .profile-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transform: scale(1.1);
            transform-origin: 50% 25%;
        }

        .profile-name {
            font-size: 24px;
            font-weight: 500;
            color: var(--text-primary);
            margin-bottom: 6px;
        }

        .profile-title {
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 0.8px;
            color: var(--accent-blue);
            margin-bottom: 16px;
            text-transform: uppercase;
        }

        .profile-desc {
            font-size: 12.5px;
            line-height: 1.5;
            color: var(--text-secondary);
            margin-bottom: 20px;
        }

        .profile-socials {
            display: flex;
            gap: 14px;
            margin-bottom: 24px;
            justify-content: center;
            align-items: center;
        }

        .social-link {
            width: 34px;
            height: 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: transform var(--transition-speed);
        }

        .social-link img {
            width: 22px;
            height: 22px;
            transition: filter var(--transition-speed);
        }

        /* Light theme invert to match the dark color style of the screenshot icons */
        .social-link img {
            filter: var(--social-icon-filter, none);
        }

        [data-theme="dark"] .social-link img {
            filter: invert(1) brightness(0.9);
        }

        .social-link:hover {
            transform: translateY(-3px);
        }

        .consultation-btn {
            display: block;
            width: 100%;
            background-color: var(--accent-blue);
            color: #ffffff;
            font-size: 13.5px;
            font-weight: 500;
            text-align: center;
            padding: 12px;
            border-radius: 30px;
            text-decoration: none;
            box-shadow: 0 4px 10px rgba(0, 130, 242, 0.2);
            transition: background-color var(--transition-speed), transform var(--transition-speed);
        }

        .consultation-btn:hover {
            background-color: var(--accent-blue-hover);
            transform: translateY(-1px);
        }

        /* Tech Badges below sidebar card */
        .tech-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 24px;
            justify-content: center;
        }

        .tech-badge {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 5px 12px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            font-weight: 500;
            color: var(--text-secondary);
            transition: all var(--transition-speed);
        }

        .tech-badge img {
            height: 12px;
            width: auto;
        }

        .tech-badge:hover {
            border-color: var(--accent-blue);
            color: var(--accent-blue);
            transform: translateY(-2px);
        }

        /* Content Column */
        .content-column {
            flex-grow: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            gap: 60px;
            max-width: 900px;
        }

        /* Top Nav Pill */
        .nav-wrapper {
            position: sticky;
            top: 20px;
            z-index: 100;
            margin-bottom: 10px;
            width: 100%;
        }

        .nav-pill-container {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 50px;
            padding: 6px 8px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            box-shadow: var(--card-shadow);
            transition: background-color 0.3s, border-color 0.3s, box-shadow 0.3s;
        }

        .nav-link {
            text-decoration: none;
            padding: 8px 18px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-secondary);
            transition: all var(--transition-speed);
            text-align: center;
            flex: 1;
        }

        .nav-link:hover {
            color: var(--text-primary);
        }

        .nav-link.active {
            color: var(--accent-blue);
            background-color: var(--accent-blue-light);
        }

        [data-theme="dark"] .nav-link.active {
            color: #ffffff;
            background-color: var(--accent-blue-light);
        }

        /* Sections Styling */
        .content-section {
            padding-top: 10px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 500;
            letter-spacing: 0.8px;
            color: var(--text-primary);
            text-transform: uppercase;
        }

        .section-divider {
            height: 1px;
            background-color: var(--border-color);
            margin: 14px 0 24px 0;
            border: none;
        }

        /* Hero / About section */
        .hero-subtitle {
            font-size: 16px;
            font-weight: 500;
            color: var(--text-secondary);
            margin-bottom: 10px;
        }

        .text-blue {
            color: var(--accent-blue);
        }

        .hero-title {
            font-size: 40px;
            font-weight: 500;
            line-height: 1.25;
            color: var(--text-primary);
            margin-bottom: 24px;
            letter-spacing: -0.5px;
        }

        .consultant-pill {
            background-color: var(--accent-blue);
            color: #ffffff;
            padding: 2px 18px;
            border-radius: 40px;
            display: inline-block;
            font-size: 38px;
            line-height: 1.1;
            font-weight: 500;
            margin-left: 2px;
            margin-right: 2px;
        }

        .stats-row {
            display: flex;
            gap: 40px;
            margin: 36px 0;
            flex-wrap: wrap;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .stat-number {
            font-size: 38px;
            font-weight: 400;
            color: var(--text-primary);
            line-height: 1;
        }

        .stat-label {
            font-size: 12px;
            color: var(--text-secondary);
            line-height: 1.3;
            font-weight: 500;
        }

        .about-label {
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 0.8px;
            color: var(--accent-blue);
            margin-bottom: 16px;
            text-transform: uppercase;
        }

        .about-desc {
            font-size: 14.5px;
            line-height: 1.7;
            color: var(--text-secondary);
            margin-bottom: 20px;
            font-weight: 400;
        }

        /* Certifications section */
        .certifications-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(90px, 1fr));
            gap: 16px;
            margin-top: 10px;
            justify-content: center;
        }

        .cert-item {
            display: flex;
            justify-content: center;
            align-items: center;
            transition: transform var(--transition-speed);
        }

        .cert-item img {
            width: 100%;
            height: auto;
            max-width: 90px;
        }

        .cert-item:hover {
            transform: scale(1.08);
        }

        /* Services section */
        .services-container {
            display: flex;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            overflow: hidden;
            margin-top: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.01);
            transition: background-color 0.3s, border-color 0.3s;
        }

        .services-sidebar {
            width: 240px;
            flex-shrink: 0;
            border-right: 1px solid var(--border-color);
            padding: 24px 16px;
            background: var(--services-sidebar-bg);
            display: flex;
            flex-direction: column;
            gap: 12px;
            transition: background-color 0.3s, border-color 0.3s;
        }

        .service-tab-btn {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 18px;
            background: none;
            border: none;
            font-family: inherit;
            font-size: 13.5px;
            font-weight: 500;
            color: var(--text-secondary);
            cursor: pointer;
            text-align: left;
            border-radius: 12px;
            transition: all var(--transition-speed);
        }

        .service-tab-btn:hover:not(.active) {
            background-color: rgba(0, 0, 0, 0.03);
            color: var(--text-primary);
        }

        [data-theme="dark"] .service-tab-btn:hover:not(.active) {
            background-color: rgba(255, 255, 255, 0.03);
        }

        .service-tab-btn.active {
            background-color: var(--bg-card);
            color: var(--accent-blue);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
        }

        .chevron-icon {
            opacity: 0;
            transition: opacity var(--transition-speed), transform var(--transition-speed);
        }

        .service-tab-btn.active .chevron-icon {
            opacity: 1;
            stroke: var(--accent-blue);
            transform: translateX(3px);
        }

        .services-content {
            flex-grow: 1;
            min-width: 0;
            padding: 32px;
            display: flex;
            flex-direction: column;
            gap: 24px;
            background: var(--bg-card);
            transition: background-color 0.3s;
        }

        .services-content-title {
            font-size: 16px;
            font-weight: 500;
            color: var(--text-primary);
        }

        .services-tags-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-content: flex-start;
        }

        .service-tag {
            background: var(--btn-bg-secondary);
            border: 1px solid transparent;
            border-radius: 10px;
            padding: 8px 16px;
            font-size: 12px;
            font-weight: 500;
            color: var(--text-secondary);
            transition: all var(--transition-speed);
        }

        .service-tag:hover {
            border-color: var(--accent-blue);
            color: var(--accent-blue);
            background: var(--accent-blue-light);
            transform: translateY(-2px);
        }

        /* Top Clients section */
        /* Top Clients section */
        .clients-slider-container {
            overflow: hidden;
            width: 100%;
            position: relative;
            padding: 16px 0;
            margin-top: 10px;
            /* Premium fade gradient at the edges */
            mask-image: linear-gradient(to right, transparent, #000 10%, #000 90%, transparent);
            -webkit-mask-image: linear-gradient(to right, transparent, #000 10%, #000 90%, transparent);
        }

        .clients-slider-track {
            display: flex;
            width: max-content;
            animation: marquee 25s linear infinite;
            will-change: transform;
        }

        .clients-slider-track:hover {
            animation-play-state: paused;
        }

        .clients-slider-list {
            display: flex;
            align-items: center;
            gap: 60px;
            padding-right: 60px;
        }

        .client-logo-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-shrink: 0;
        }

        .client-logo {
            height: 32px;
            max-width: 130px;
            object-fit: contain;
            transition: transform var(--transition-speed);
        }

        .client-logo:hover {
            transform: scale(1.08);
        }

        @keyframes marquee {
            0% {
                transform: translate3d(0, 0, 0);
            }

            100% {
                transform: translate3d(-50%, 0, 0);
            }
        }

        /* Contact section */
        .contact-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 10px;
        }

        .contact-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 20px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            text-decoration: none;
            transition: all var(--transition-speed);
        }

        .contact-card:hover {
            transform: translateY(-3px);
            border-color: var(--accent-blue);
            box-shadow: var(--card-shadow);
        }

        .contact-card-full {
            grid-column: span 2;
        }

        .contact-card-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .contact-icon {
            width: 24px;
            height: 24px;
            object-fit: contain;
        }

        [data-theme="dark"] .contact-icon {
            filter: invert(1) brightness(0.9);
        }

        .contact-card-label {
            font-size: 13px;
            font-weight: 500;
            color: var(--text-muted);
        }

        .contact-card-detail {
            font-size: 13px;
            font-weight: 500;
            color: var(--text-secondary);
            text-align: right;
        }

        /* Contact Form */
        .contact-form-section {
            margin-top: 40px;
            padding-bottom: 80px;
        }

        .form-title {
            font-size: 26px;
            font-weight: 500;
            color: var(--text-primary);
            margin-bottom: 24px;
        }

        .project-contact-form {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            padding: 14px 18px;
            border-radius: 12px;
            font-size: 13px;
            color: var(--text-primary);
            font-family: inherit;
            transition: all var(--transition-speed);
        }

        .form-group textarea {
            height: 120px;
            resize: vertical;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--accent-blue);
            box-shadow: 0 0 0 3px var(--accent-blue-light);
        }

        .submit-btn {
            align-self: flex-start;
            background: var(--btn-bg-secondary);
            color: var(--btn-text-secondary);
            border: none;
            border-radius: 30px;
            padding: 12px 24px;
            font-size: 12px;
            font-weight: 500;
            letter-spacing: 0.5px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all var(--transition-speed);
            font-family: inherit;
        }

        .submit-btn:hover {
            background: var(--accent-blue);
            color: #ffffff;
            transform: translateY(-2px);
        }

        .submit-btn svg {
            transition: transform var(--transition-speed);
        }

        .submit-btn:hover svg {
            transform: translateX(3px);
        }

        /* Responsive Breakpoints */
        @media (min-width: 993px) and (max-width: 1200px) {
            .app-layout {
                gap: 30px;
                padding: 30px 20px;
            }

            .sidebar-column {
                width: 340px;
            }

            .profile-card {
                padding: 20px;
            }

            .profile-name {
                font-size: 22px;
            }

            .profile-desc {
                font-size: 12px;
            }

            .nav-link {
                padding: 8px 12px;
                font-size: 12px;
            }

            .stats-row {
                gap: 20px;
                margin: 24px 0;
            }
        }

        @media (max-width: 992px) {
            .app-layout {
                flex-direction: column;
                gap: 40px;
                padding: 30px 20px;
            }

            .sidebar-column {
                width: 100%;
                position: relative;
                top: 0;
            }

            .profile-card {
                max-width: 600px;
                margin: 0 auto;
            }

            .tech-badges {
                max-width: 600px;
                margin: 24px auto 0 auto;
            }

            .nav-wrapper {
                position: sticky;
                top: 10px;
                width: 100%;
                display: flex;
                justify-content: center;
            }

            .nav-pill-container {
                width: 100%;
                max-width: 100%;
                display: flex;
                justify-content: space-between;
                overflow-x: auto;
                white-space: nowrap;
                flex-wrap: nowrap;
                -webkit-overflow-scrolling: touch;
                scrollbar-width: none;
                /* Hide scrollbar for Firefox */
            }

            .nav-pill-container::-webkit-scrollbar {
                display: none;
                /* Hide scrollbar for Chrome, Safari, Opera */
            }

            .nav-link {
                flex: 0 0 auto;
                padding: 8px 16px;
            }
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 32px;
            }

            .consultant-pill {
                font-size: 30px;
                padding: 1px 14px;
            }

            .services-container {
                flex-direction: column;
            }

            .services-sidebar {
                width: 100%;
                border-right: none;
                border-bottom: 1px solid var(--border-color);
                padding: 12px;
                flex-direction: row;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                gap: 8px;
                scrollbar-width: none;
                /* Hide scrollbar for Firefox */
            }

            .services-sidebar::-webkit-scrollbar {
                display: none;
                /* Hide scrollbar for Chrome, Safari, Opera */
            }

            .service-tab-btn {
                width: auto;
                flex: 0 0 auto;
                padding: 8px 16px;
                font-size: 13px;
                justify-content: center;
            }

            .chevron-icon {
                display: none;
                /* Hide chevron on horizontal tabs */
            }

            .services-content {
                padding: 24px 16px;
                gap: 16px;
            }

            .contact-grid {
                grid-template-columns: 1fr;
            }

            .contact-card-full {
                grid-column: span 1;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .clients-slider-list {
                gap: 40px;
                padding-right: 40px;
            }

            .client-logo {
                height: 26px;
            }
        }

        @media (max-width: 480px) {
            .app-layout {
                padding: 15px 12px;
                gap: 30px;
            }

            .nav-pill-container {
                padding: 4px;
            }

            .nav-link {
                padding: 6px 12px;
                font-size: 12px;
            }

            .hero-title {
                font-size: 26px;
            }

            .consultant-pill {
                font-size: 24px;
                padding: 0 10px;
            }

            .stats-row {
                gap: 20px;
                justify-content: space-between;
            }

            .stat-item {
                flex: 1 1 30%;
                min-width: 90px;
                flex-direction: column;
                align-items: flex-start;
                gap: 4px;
            }

            .stat-number {
                font-size: 28px;
            }

            .stat-label {
                font-size: 11px;
            }

            .clients-slider-list {
                gap: 30px;
                padding-right: 30px;
            }

            .client-logo {
                height: 22px;
            }

            .contact-card {
                padding: 16px;
            }

            .contact-card-label {
                font-size: 12px;
            }

            .contact-card-detail {
                font-size: 12px;
                word-break: break-word;
                line-height: 1.4;
            }
        }
    </style>
</head>

<body>

    <div class="app-layout">
        <!-- Sidebar Column -->
        <aside class="sidebar-column">
            <div class="profile-card">
                <div class="sidebar-header">
                    <button class="icon-btn" aria-label="Menu">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="4" y1="12" x2="20" y2="12"></line>
                            <line x1="4" y1="6" x2="20" y2="6"></line>
                            <line x1="4" y1="18" x2="20" y2="18"></line>
                        </svg>
                    </button>
                    <button class="icon-btn theme-toggle" id="theme-toggle-btn" aria-label="Toggle Theme">
                        <svg class="moon-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                        </svg>
                        <svg class="sun-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:none;">
                            <circle cx="12" cy="12" r="5"></circle>
                            <line x1="12" y1="1" x2="12" y2="3"></line>
                            <line x1="12" y1="21" x2="12" y2="23"></line>
                            <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                            <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                            <line x1="1" y1="12" x2="3" y2="12"></line>
                            <line x1="21" y1="12" x2="23" y2="12"></line>
                            <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                            <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
                        </svg>
                    </button>
                </div>
                <div class="profile-img-container">
                    <img src="profile_cutout.png" alt="Shubham Das" class="profile-img" id="avatar-img" />
                </div>
                <h1 class="profile-name">Shubham Das</h1>
                <div class="profile-title">Salesforce Consultant & Project Lead</div>
                <p class="profile-desc">
                    10x Certified | Sales, Service, Marketing & Data Cloud + Agentforce | Apex • LWC | HubSpot | Helping businesses turn CRM into revenue
                </p>
                <div class="profile-socials">
                    <a href="https://wa.me/+919583162067" target="_blank" class="social-link" title="WhatsApp">
                        <img src="images/social-media/whatsapp.svg" alt="WhatsApp">
                    </a>
                    <a href="https://www.linkedin.com/in/shubhamdas28/" target="_blank" class="social-link" title="LinkedIn">
                        <img src="images/social-media/linkedin.svg" alt="LinkedIn">
                    </a>
                    <a href="https://cccinfotech.slack.com/team/U06BBKN6S73" target="_blank" class="social-link" title="Slack">
                        <img src="images/social-media/slack.svg" alt="Slack">
                    </a>
                    <a href="mailto:shubham@cccinfotech.com" class="social-link" title="Email">
                        <img src="images/social-media/mail.svg" alt="Email">
                    </a>
                    <a href="http://cccinfotech.com/" target="_blank" class="social-link" title="Website">
                        <img src="images/social-media/web.svg" alt="Website">
                    </a>
                </div>
                <a href="https://wa.me/+919583162067" target="_blank" class="consultation-btn">Get Free Consultation</a>
            </div>

            <!-- Technology Skill Badges -->
            <div class="tech-badges">
                <div class="tech-badge">
                    <img src="images/salesforce-icons/sales-cloud.svg" alt="Sales Cloud">
                    <span>Sales Cloud</span>
                </div>
                <div class="tech-badge">
                    <img src="images/salesforce-icons/service-cloud.svg" alt="Service Cloud">
                    <span>Service Cloud</span>
                </div>
                <div class="tech-badge">
                    <img src="images/salesforce-icons/marketing-cloud.svg" alt="Marketing Cloud">
                    <span>Marketing Cloud</span>
                </div>
                <div class="tech-badge">
                    <img src="images/salesforce-icons/data-cloud.svg" alt="Data Cloud">
                    <span>Data Cloud</span>
                </div>
                <div class="tech-badge">
                    <img src="images/salesforce-icons/experience-cloud.svg" alt="Experience Cloud">
                    <span>Experience Cloud</span>
                </div>
                <div class="tech-badge">
                    <img src="images/salesforce-icons/agentforce-icon.svg" alt="Agentforce">
                    <span>Agentforce</span>
                </div>
                <div class="tech-badge">
                    <img src="images/salesforce-icons/salesforce-icon.svg" alt="Apex">
                    <span>Apex</span>
                </div>
                <div class="tech-badge">
                    <img src="images/salesforce-icons/lwc-icon.svg" alt="LWC">
                    <span>LWC</span>
                </div>
                <div class="tech-badge">
                    <img src="images/salesforce-icons/hubspot-icon.svg" alt="HubSpot">
                    <span>HubSpot</span>
                </div>
            </div>
        </aside>

        <!-- Content Column -->
        <main class="content-column">
            <!-- Sticky Navigation -->
            <div class="nav-wrapper">
                <nav class="nav-pill-container" id="main-nav">
                    <a href="#about" class="nav-link active">About</a>
                    <a href="#certifications" class="nav-link">Certifications</a>
                    <a href="#services" class="nav-link">Services</a>
                    <a href="#clients" class="nav-link">Clients</a>
                    <a href="#contact" class="nav-link">Contact</a>
                </nav>
            </div>

            <!-- About / Hero Section -->
            <section id="about" class="content-section">
                <p class="hero-subtitle">Hello, I’m <span class="text-blue">Shubham</span></p>
                <h1 class="hero-title">
                    Certified Salesforce &<br>
                    Artificial Intelligence <span class="consultant-pill">Consultant</span><br>
                    Based in Noida, India.
                </h1>

                <!-- Statistics Row -->
                <div class="stats-row">
                    <div class="stat-item">
                        <span class="stat-number">10+</span>
                        <span class="stat-label">Years of<br>Experience</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">50+</span>
                        <span class="stat-label">Completed<br>Projects</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">10x</span>
                        <span class="stat-label">Certified</span>
                    </div>
                </div>

                <div class="about-label">ABOUT ME</div>
                <p class="about-desc">
                    Salesforce Consultant and Project Lead with 10+ years designing, building, and scaling CRM solutions for growing businesses at CloudCentric Infotech — a Summit Salesforce Partner.
                </p>
                <p class="about-desc">
                    I lead end-to-end Salesforce delivery at CloudCentric Infotech from requirements and solution design through build, QA, and go-live. Managing teams of up to 10 developers and admins across multiple industries. I'm 10x Salesforce certified with hands-on depth in Apex, Lightning Web Components, configuration and automation, and I also consult on HubSpot CRM.
                </p>
            </section>

            <!-- Certifications Section -->
            <section id="certifications" class="content-section">
                <h2 class="section-title">CERTIFICATIONS</h2>
                <hr class="section-divider">
                <div class="certifications-grid">
                    <div class="cert-item" title="Certified Agentforce Specialist">
                        <img src="images/certificates/agentforce-specialist.svg" alt="Certified Agentforce Specialist">
                    </div>
                    <div class="cert-item" title="Certified AI Associate">
                        <img src="images/certificates/ai-associate.svg" alt="Certified AI Associate">
                    </div>
                    <div class="cert-item" title="Certified Data 360 Consultant">
                        <img src="images/certificates/data-360-consultant.svg" alt="Certified Data 360 Consultant">
                    </div>
                    <div class="cert-item" title="Certified Platform Administrator">
                        <img src="images/certificates/platform-administrator.svg" alt="Certified Platform Administrator">
                    </div>
                    <div class="cert-item" title="Certified Platform Developer">
                        <img src="images/certificates/platform-developer.svg" alt="Certified Platform Developer">
                    </div>
                    <div class="cert-item" title="Certified Platform Developer II">
                        <img src="images/certificates/platform-developer-2.svg" alt="Certified Platform Developer II">
                    </div>
                    <div class="cert-item" title="Certified Platform Foundations">
                        <img src="images/certificates/platform-foundations.svg" alt="Certified Platform Foundations">
                    </div>
                    <div class="cert-item" title="Certified Agentforce Sales Consultant">
                        <img src="images/certificates/agentforce-sales-consultant.svg" alt="Certified Agentforce Sales Consultant">
                    </div>
                    <div class="cert-item" title="Certified Agentforce Service Consultant">
                        <img src="images/certificates/agentforce-service-consultant.svg" alt="Certified Agentforce Service Consultant">
                    </div>
                </div>
            </section>

            <!-- Services Section -->
            <section id="services" class="content-section">
                <h2 class="section-title">SERVICES</h2>
                <hr class="section-divider">
                <div class="services-container">
                    <div class="services-sidebar">
                        <button class="service-tab-btn active" data-tab="salesforce-services">
                            <span>Salesforce Services</span>
                            <svg class="chevron-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </button>
                        <button class="service-tab-btn" data-tab="salesforce-clouds">
                            <span>Salesforce Clouds</span>
                            <svg class="chevron-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </button>
                        <button class="service-tab-btn" data-tab="salesforce-products">
                            <span>Salesforce Products</span>
                            <svg class="chevron-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </button>
                    </div>
                    <div class="services-content" id="services-tags-container">
                        <!-- Render tags dynamically here via JS -->
                    </div>
                </div>
            </section>

            <!-- Top Clients Section -->
            <section id="clients" class="content-section">
                <h2 class="section-title">TOP CLIENTS</h2>
                <hr class="section-divider">
                <div class="clients-slider-container">
                    <div class="clients-slider-track">
                        <div class="clients-slider-list">
                            <div class="client-logo-wrapper">
                                <img src="images/clients/codingdepartment.svg" alt="Coding Department" class="client-logo">
                            </div>
                            <div class="client-logo-wrapper">
                                <img src="images/clients/novo.svg" alt="Novo" class="client-logo">
                            </div>
                            <div class="client-logo-wrapper">
                                <img src="images/clients/sirona.svg" alt="Sirona" class="client-logo">
                            </div>
                            <div class="client-logo-wrapper">
                                <img src="images/clients/ferrum.svg" alt="Ferrum" class="client-logo">
                            </div>
                            <div class="client-logo-wrapper">
                                <img src="images/clients/aidoc.svg" alt="Aidoc" class="client-logo">
                            </div>
                        </div>
                        <div class="clients-slider-list">
                            <div class="client-logo-wrapper">
                                <img src="images/clients/codingdepartment.svg" alt="Coding Department" class="client-logo">
                            </div>
                            <div class="client-logo-wrapper">
                                <img src="images/clients/novo.svg" alt="Novo" class="client-logo">
                            </div>
                            <div class="client-logo-wrapper">
                                <img src="images/clients/sirona.svg" alt="Sirona" class="client-logo">
                            </div>
                            <div class="client-logo-wrapper">
                                <img src="images/clients/ferrum.svg" alt="Ferrum" class="client-logo">
                            </div>
                            <div class="client-logo-wrapper">
                                <img src="images/clients/aidoc.svg" alt="Aidoc" class="client-logo">
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Contact Section -->
            <section id="contact" class="content-section">
                <h2 class="section-title">CONTACT</h2>
                <hr class="section-divider">
                <div class="contact-grid">
                    <a href="tel:+919583162067" class="contact-card">
                        <div class="contact-card-left">
                            <img src="images/contact/phone-icon.svg" alt="Phone" class="contact-icon">
                            <span class="contact-card-label">Phone</span>
                        </div>
                        <span class="contact-card-detail">+91 9583162067</span>
                    </a>
                    <a href="mailto:shubham@cccinfotech.com" class="contact-card">
                        <div class="contact-card-left">
                            <img src="images/contact/email-icon.svg" alt="Email" class="contact-icon">
                            <span class="contact-card-label">Email</span>
                        </div>
                        <span class="contact-card-detail">SHUBHAM@CCCINFOTECH.COM</span>
                    </a>
                    <div class="contact-card contact-card-full">
                        <div class="contact-card-left">
                            <img src="images/contact/address-icon.svg" alt="Address" class="contact-icon">
                            <span class="contact-card-label">Address</span>
                        </div>
                        <span class="contact-card-detail">H-146 & 147, H BLOCK, SECTOR 63, NOIDA, UTTAR PRADESH 201309</span>
                    </div>
                </div>

                <!-- Contact Form CTA -->
                <div class="contact-form-section">
                    <h3 class="form-title">Let's make your project brilliant!</h3>
                    <form class="project-contact-form" id="contact-form" action="#" method="POST">
                        <div class="form-row">
                            <div class="form-group">
                                <input type="text" id="fullname" name="fullname" placeholder="Full Name" required>
                            </div>
                            <div class="form-group">
                                <input type="email" id="email" name="email" placeholder="Email Address" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <textarea id="message" name="message" placeholder="Your Message" required></textarea>
                        </div>
                        <button type="submit" class="submit-btn">
                            <span>SEND MESSAGE</span>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </button>
                    </form>
                </div>
            </section>
        </main>
    </div>

    <script>
        // Use proper photo if available
        const img = document.getElementById('avatar-img');
        img.onerror = function() {
            this.src = 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 24 24" fill="%23475569"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 4c1.93 0 3.5 1.57 3.5 3.5S13.93 13 12 13s-3.5-1.57-3.5-3.5S10.07 6 12 6zm0 14c-2.03 0-4.43-.82-6.14-2.88C7.55 15.8 9.68 15 12 15s4.45.8 6.14 2.12C16.43 19.18 14.03 20 12 20z"/></svg>';
        }

        // Theme Toggle Script
        const themeToggleBtn = document.getElementById('theme-toggle-btn');
        const moonIcon = document.querySelector('.moon-icon');
        const sunIcon = document.querySelector('.sun-icon');

        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-theme', savedTheme);
        updateThemeIcons(savedTheme);

        themeToggleBtn.addEventListener('click', () => {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateThemeIcons(newTheme);
        });

        function updateThemeIcons(theme) {
            if (theme === 'dark') {
                moonIcon.style.display = 'none';
                sunIcon.style.display = 'block';
            } else {
                moonIcon.style.display = 'block';
                sunIcon.style.display = 'none';
            }
        }

        // Services Tab Switcher Script
        const servicesData = {
            'salesforce-services': [
                'Salesforce Consulting',
                'Salesforce Implementation',
                'Salesforce Development',
                'Salesforce Integration',
                'Salesforce Data Migration',
                'Salesforce Training',
                'Salesforce AppExchange',
                'Hire Salesforce Consultant',
                'Salesforce Reseller',
                'Salesforce Mobile SDK Development',
                'Salesforce Support'
            ],
            'salesforce-clouds': [
                'Sales Cloud',
                'Service Cloud',
                'Marketing Cloud',
                'Commerce Cloud',
                'Experience Cloud',
                'Data Cloud',
                'Financial Services Cloud',
                'Health Cloud',
                'Education Cloud',
                'Non-Profit Cloud'
            ],
            'salesforce-products': [
                'Agentforce',
                'MuleSoft',
                'Tableau',
                'Slack',
                'CPQ & Billing',
                'Field Service Lightning',
                'Einstein AI & Analytics',
                'HubSpot Integration',
                'Salesforce Shield'
            ]
        };

        const tabButtons = document.querySelectorAll('.service-tab-btn');
        const tagsContainer = document.getElementById('services-tags-container');

        function renderTags(tabName) {
            tagsContainer.innerHTML = '';

            // Get human-readable title
            let titleText = '';
            if (tabName === 'salesforce-services') titleText = 'Salesforce Services';
            else if (tabName === 'salesforce-clouds') titleText = 'Salesforce Clouds';
            else if (tabName === 'salesforce-products') titleText = 'Salesforce Products';

            // Create title header
            const titleEl = document.createElement('h3');
            titleEl.className = 'services-content-title';
            titleEl.textContent = titleText;
            tagsContainer.appendChild(titleEl);

            // Create wrapper for tags
            const tagsList = document.createElement('div');
            tagsList.className = 'services-tags-list';

            const tags = servicesData[tabName] || [];
            tags.forEach(tag => {
                const span = document.createElement('span');
                span.className = 'service-tag';
                span.textContent = tag;
                tagsList.appendChild(span);
            });
            tagsContainer.appendChild(tagsList);
        }

        renderTags('salesforce-services');

        tabButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                tabButtons.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                renderTags(btn.getAttribute('data-tab'));
            });
        });

        // Navigation Scrollspy Script
        const sections = document.querySelectorAll('section');
        const navLinks = document.querySelectorAll('.nav-link');
        const navContainer = document.getElementById('main-nav');

        function centerActiveNavLink() {
            const activeLink = navContainer.querySelector('.nav-link.active');
            if (activeLink) {
                const containerWidth = navContainer.offsetWidth;
                const linkLeft = activeLink.offsetLeft;
                const linkWidth = activeLink.offsetWidth;
                navContainer.scrollTo({
                    left: linkLeft - (containerWidth / 2) + (linkWidth / 2),
                    behavior: 'smooth'
                });
            }
        }

        window.addEventListener('scroll', () => {
            let current = 'about';
            const pageYOffset = window.pageYOffset;

            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                if (pageYOffset >= (sectionTop - 150)) {
                    current = section.getAttribute('id');
                }
            });

            let changed = false;
            navLinks.forEach(link => {
                const wasActive = link.classList.contains('active');
                const isNowActive = link.getAttribute('href') === `#${current}`;
                if (isNowActive) {
                    link.classList.add('active');
                    if (!wasActive) changed = true;
                } else {
                    link.classList.remove('active');
                }
            });

            if (changed) {
                centerActiveNavLink();
            }
        });

        // Center active link when direct clicked
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                navLinks.forEach(l => l.classList.remove('active'));
                link.classList.add('active');
                centerActiveNavLink();
            });
        });

        // Center active link on initial load
        window.addEventListener('load', centerActiveNavLink);
    </script>
</body>

</html>