<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HireBridge - Connect Talent with Opportunity</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            color: #1a1a2e;
            overflow-x: hidden;
        }

        /* Navigation */
        nav {
            position: fixed;
            width: 100%;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 1.2rem 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.05);
        }

        .logo {
            font-size: 1.8rem;
            font-weight: 700;
            color: #1a1a2e;
        }

        .logo span {
            color: #6366f1;
        }

        .nav-links {
            display: flex;
            gap: 2.5rem;
            list-style: none;
            align-items: center;
        }

        .nav-links a {
            text-decoration: none;
            color: #4a5568;
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: #6366f1;
        }

        .nav-links .btn-primary{
            color: rgb(234, 228, 228);
        }

        .nav-links .btn-primary:hover{
            color: white;
        }

        .btn-primary {
            background: #6366f1;
            color: white;
            padding: 0.75rem 1.8rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }

        

        .btn-primary:hover {
            background: #4f46e5;
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(99, 102, 241, 0.3);
        }

        .btn-secondary {
            background: transparent;
            color: #6366f1;
            padding: 0.75rem 1.8rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            border: 2px solid #6366f1;
            transition: all 0.3s;
        }

        .btn-secondary:hover {
            background: #6366f1;
            color: white;
        }

        /* Hero Section */
        .hero {
            min-height: 100vh;
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);
            display: flex;
            align-items: center;
            padding: 8rem 5% 4rem;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.2) 0%, transparent 70%);
            border-radius: 50%;
            top: -200px;
            right: -200px;
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-30px); }
        }

        .hero-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }

        .hero-text h1 {
            font-size: 3.5rem;
            color: white;
            line-height: 1.2;
            margin-bottom: 1.5rem;
            font-weight: 800;
        }

        .hero-text h1 .highlight {
            background: linear-gradient(120deg, #6366f1, #a855f7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-text p {
            font-size: 1.25rem;
            color: #cbd5e1;
            margin-bottom: 2.5rem;
            line-height: 1.8;
        }

        .hero-buttons {
            display: flex;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        .hero-image {
            position: relative;
        }

        .hero-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: slideInRight 0.8s ease-out;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .stat-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-top: 1.5rem;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: white;
            display: block;
        }

        .stat-label {
            color: #cbd5e1;
            font-size: 0.9rem;
        }

        /* Features Section */
        .features {
            padding: 6rem 5%;
            background: #f8fafc;
        }

        .section-header {
            text-align: center;
            max-width: 700px;
            margin: 0 auto 4rem;
        }

        .section-header h2 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: #1e1b4b;
        }

        .section-header p {
            font-size: 1.1rem;
            color: #64748b;
        }

        .features-grid {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2.5rem;
        }

        .feature-card {
            background: white;
            padding: 2.5rem;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s;
            border: 1px solid #e2e8f0;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(99, 102, 241, 0.15);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #6366f1, #a855f7);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-bottom: 1.5rem;
        }

        .feature-card h3 {
            font-size: 1.4rem;
            margin-bottom: 1rem;
            color: #1e1b4b;
        }

        .feature-card p {
            color: #64748b;
            line-height: 1.7;
        }

        /* How It Works */
        .how-it-works {
            padding: 6rem 5%;
            background: white;
        }

        .steps-container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .step {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
            margin-bottom: 4rem;
        }

        .step:nth-child(even) {
            direction: rtl;
        }

        .step:nth-child(even) > * {
            direction: ltr;
        }

        .step-number {
            font-size: 4rem;
            font-weight: 800;
            background: linear-gradient(135deg, #6366f1, #a855f7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1rem;
        }

        .step-content h3 {
            font-size: 1.8rem;
            margin-bottom: 1rem;
            color: #1e1b4b;
        }

        .step-content p {
            color: #64748b;
            line-height: 1.8;
            font-size: 1.1rem;
        }

        .step-visual {
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
            border-radius: 16px;
            padding: 3rem;
            min-height: 250px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
        }

        /* CTA Section */
        .cta {
            padding: 6rem 5%;
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);
            text-align: center;
            color: white;
        }

        .cta h2 {
            font-size: 3rem;
            margin-bottom: 1.5rem;
        }

        .cta p {
            font-size: 1.2rem;
            color: #cbd5e1;
            margin-bottom: 2.5rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Footer */
        footer {
            background: #0f172a;
            color: #94a3b8;
            padding: 3rem 5%;
            text-align: center;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .footer-section h4 {
            color: white;
            margin-bottom: 1rem;
        }

        .footer-section a {
            display: block;
            color: #94a3b8;
            text-decoration: none;
            margin-bottom: 0.5rem;
            transition: color 0.3s;
        }

        .footer-section a:hover {
            color: #6366f1;
        }

        .footer-bottom {
            border-top: 1px solid #1e293b;
            padding-top: 2rem;
            margin-top: 2rem;
        }

        @media (max-width: 768px) {
            .hero-content {
                grid-template-columns: 1fr;
            }

            .hero-text h1 {
                font-size: 2.5rem;
            }

            .step {
                grid-template-columns: 1fr;
            }

            .nav-links {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav>
        <div class="logo">Hire<span>Bridge</span></div>
        <ul class="nav-links">
            <li><a href="#features">Features</a></li>
            <li><a href="#how-it-works">How It Works</a></li>
            <li><a href="#pricing">Pricing</a></li>
            <li><a href="loginScreen.php" class="btn-primary">Get Started</a></li>
        </ul>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <div class="hero-text">
                <h1>Connect <span class="highlight">Top Talent</span> with Outstanding Opportunities</h1>
                <p>Transform your hiring process with HireBridge. Build powerful company profiles, attract the best candidates, and streamline your recruitment journey.</p>
                <div class="hero-buttons">
                    <a href="#" class="btn-primary">Start Free Trial</a>
                    <a href="#" class="btn-secondary">Watch Demo</a>
                </div>
            </div>
            <div class="hero-image">
                <div class="hero-card">
                    <h3 style="color: white; margin-bottom: 1rem;">Trusted by Industry Leaders</h3>
                    <div class="stat-grid">
                        <div class="stat-item">
                            <span class="stat-number">10K+</span>
                            <span class="stat-label">Companies</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">500K+</span>
                            <span class="stat-label">Candidates</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">95%</span>
                            <span class="stat-label">Success Rate</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">50+</span>
                            <span class="stat-label">Countries</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <div class="section-header">
            <h2>Everything You Need to Hire Smarter</h2>
            <p>Powerful features designed to streamline your recruitment process and connect you with top talent.</p>
        </div>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">🏢</div>
                <h3>Company Profiles</h3>
                <p>Build compelling company profiles that showcase your brand, culture, and values to attract the right candidates.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🎯</div>
                <h3>Smart Matching</h3>
                <p>AI-powered algorithms match candidates with opportunities based on skills, experience, and cultural fit.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📊</div>
                <h3>Analytics Dashboard</h3>
                <p>Track your hiring metrics, candidate pipeline, and team performance with real-time analytics.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">💬</div>
                <h3>Seamless Communication</h3>
                <p>Integrated messaging and video interviews keep all communications in one place.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🔒</div>
                <h3>Enterprise Security</h3>
                <p>Bank-level encryption and compliance with global data protection standards.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🚀</div>
                <h3>Fast Integration</h3>
                <p>Connect with your existing HR tools and ATS systems in minutes, not weeks.</p>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="how-it-works" id="how-it-works">
        <div class="section-header">
            <h2>How HireBridge Works</h2>
            <p>Get started in three simple steps and transform your hiring process today.</p>
        </div>
        <div class="steps-container">
            <div class="step">
                <div class="step-content">
                    <div class="step-number">01</div>
                    <h3>Create Your Profile</h3>
                    <p>Build your company presence with a comprehensive profile. Upload your logo, share your story, and highlight what makes your company unique.</p>
                </div>
                <div class="step-visual">📝</div>
            </div>
            <div class="step">
                <div class="step-content">
                    <div class="step-number">02</div>
                    <h3>Post Opportunities</h3>
                    <p>Create detailed job listings that attract the right talent. Our smart tools help you reach qualified candidates faster.</p>
                </div>
                <div class="step-visual">📢</div>
            </div>
            <div class="step">
                <div class="step-content">
                    <div class="step-number">03</div>
                    <h3>Connect & Hire</h3>
                    <p>Review applications, conduct interviews, and make offers—all within the HireBridge platform. Hiring has never been easier.</p>
                </div>
                <div class="step-visual">🤝</div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <h2>Ready to Transform Your Hiring?</h2>
        <p>Join thousands of companies who have streamlined their recruitment process with HireBridge.</p>
        <div class="hero-buttons" style="justify-content: center;">
            <a href="#" class="btn-primary" style="background: white; color: #6366f1;">Start Free Trial</a>
            <a href="#" class="btn-secondary" style="border-color: white; color: white;">Schedule Demo</a>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h4>Product</h4>
                <a href="#">Features</a>
                <a href="#">Pricing</a>
                <a href="#">Security</a>
                <a href="#">Integrations</a>
            </div>
            <div class="footer-section">
                <h4>Company</h4>
                <a href="#">About Us</a>
                <a href="#">Careers</a>
                <a href="#">Blog</a>
                <a href="#">Press</a>
            </div>
            <div class="footer-section">
                <h4>Resources</h4>
                <a href="#">Help Center</a>
                <a href="#">Documentation</a>
                <a href="#">API Reference</a>
                <a href="#">Community</a>
            </div>
            <div class="footer-section">
                <h4>Legal</h4>
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
                <a href="#">Cookie Policy</a>
                <a href="#">GDPR</a>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 HireBridge. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>