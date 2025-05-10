<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BrightPath - Interactive Learning Platform</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        .animate-blob {
            animation: blob 7s infinite;
        }
        .animation-delay-2000 {
            animation-delay: 2s;
        }
        .animation-delay-4000 {
            animation-delay: 4s;
        }
        @keyframes blob {
            0% {
                transform: translate(0px, 0px) scale(1);
            }
            33% {
                transform: translate(30px, -50px) scale(1.1);
            }
            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }
            100% {
                transform: translate(0px, 0px) scale(1);
            }
        }
        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        @keyframes floating {
            0% { transform: translateY(0px) rotate(-2deg); }
            50% { transform: translateY(-15px) rotate(0deg); }
            100% { transform: translateY(0px) rotate(-2deg); }
        }
        .slide-in-left {
            animation: slideInLeft 1s ease-out;
        }
        .slide-in-right {
            animation: slideInRight 1s ease-out;
        }
        @keyframes slideInLeft {
            0% { transform: translateX(-100px); opacity: 0; }
            100% { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideInRight {
            0% { transform: translateX(100px); opacity: 0; }
            100% { transform: translateX(0); opacity: 1; }
        }
        .fade-in {
            animation: fadeIn 1.5s ease-out;
        }
        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }
        .scale-in {
            animation: scaleIn 0.5s ease-out;
        }
        @keyframes scaleIn {
            0% { transform: scale(0.8); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
        .pulse-slow {
            animation: pulseSlow 3s infinite;
        }
        @keyframes pulseSlow {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
    </style>
</head>
<body class="font-[Poppins] antialiased text-gray-800 overflow-x-hidden">

  <!-- Header -->
  <?php echo $__env->make('components.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  <!-- Main Content -->
  <div class="bg-gradient-to-b from-blue-50 to-white">
    <!-- Hero Section -->
    <section class="relative overflow-hidden min-h-screen flex items-center">
      <!-- Background Elements -->
      <div class="absolute inset-0 z-0">
        <div class="absolute top-20 left-10 w-72 h-72 bg-blue-100 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
        <div class="absolute top-40 right-10 w-72 h-72 bg-cyan-100 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-8 left-1/2 w-72 h-72 bg-indigo-100 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-4000"></div>

        <!-- Decorative Elements -->
        <div class="hidden lg:block absolute top-40 left-20 transform rotate-12">
            <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-blue-500 opacity-50">
                <circle cx="20" cy="20" r="8" stroke="currentColor" stroke-width="2"/>
                <circle cx="20" cy="20" r="16" stroke="currentColor" stroke-width="2" stroke-dasharray="4 4"/>
            </svg>
        </div>
        <div class="hidden lg:block absolute bottom-40 right-20 transform -rotate-12">
            <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-indigo-500 opacity-50">
                <rect x="10" y="10" width="20" height="20" stroke="currentColor" stroke-width="2"/>
                <rect x="4" y="4" width="32" height="32" stroke="currentColor" stroke-width="2" stroke-dasharray="4 4"/>
            </svg>
        </div>
      </div>

      <!-- Hero Content -->
      <div class="container mx-auto px-4 py-20 relative z-10">
        <div class="flex flex-col md:flex-row items-center">
          <div class="md:w-1/2 mb-10 md:mb-0 slide-in-left">
            <span class="inline-block px-3 py-1 bg-blue-100 text-blue-600 rounded-full text-sm font-semibold mb-4">
              Next-Gen Learning Platform
            </span>
            <h1 class="text-4xl md:text-5xl font-extrabold text-blue-600 leading-tight mb-4">
              Transform Your <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Learning Experience</span>
            </h1>
            <p class="text-xl text-gray-600 mb-8 max-w-lg">
              BrightPath offers an interactive learning platform with AI-generated quizzes and secure exam environments powered by facial recognition.
            </p>
            <div class="flex flex-wrap gap-4">
              <a href="/courses" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition duration-300 transform hover:-translate-y-1 scale-in">
                <span class="flex items-center">
                  <span>Explore Courses</span>
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                  </svg>
                </span>
              </a>
              <a href="/register" class="px-6 py-3 bg-white text-blue-600 font-semibold rounded-lg shadow-md border border-blue-200 hover:bg-blue-50 transition duration-300 transform hover:-translate-y-1 scale-in" style="animation-delay: 0.2s">
                <span class="flex items-center">
                  <span>Join Now</span>
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                  </svg>
                </span>
              </a>
            </div>

            <!-- Stats -->
            <div class="flex flex-wrap gap-6 mt-10">
              <div class="flex items-center">
                <div class="text-2xl font-bold text-blue-600">10K+</div>
                <div class="ml-2 text-sm text-gray-600">Students</div>
              </div>
              <div class="flex items-center">
                <div class="text-2xl font-bold text-blue-600">200+</div>
                <div class="ml-2 text-sm text-gray-600">Courses</div>
              </div>
              <div class="flex items-center">
                <div class="text-2xl font-bold text-blue-600">95%</div>
                <div class="ml-2 text-sm text-gray-600">Satisfaction</div>
              </div>
            </div>
          </div>
          <div class="md:w-1/2 slide-in-right">
            <div class="relative">
              <img src="https://images.unsplash.com/photo-1501504905252-473c47e087f8?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Online Learning" class="rounded-xl shadow-2xl floating">

              <!-- Floating Elements -->
              <div class="absolute -top-6 -right-6 bg-white rounded-lg shadow-lg p-4 scale-in" style="animation-delay: 0.4s">
                <div class="flex items-center">
                  <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </div>
                  <div>
                    <div class="text-sm font-semibold">AI-Powered</div>
                    <div class="text-xs text-gray-500">Smart Learning</div>
                  </div>
                </div>
              </div>

              <div class="absolute -bottom-6 -left-6 bg-white rounded-lg shadow-lg p-4 scale-in" style="animation-delay: 0.6s">
                <div class="flex items-center">
                  <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                  </div>
                  <div>
                    <div class="text-sm font-semibold">Secure Exams</div>
                    <div class="text-xs text-gray-500">Face Recognition</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Scroll Down Indicator -->
        <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 flex flex-col items-center fade-in" style="animation-delay: 1s">
          <span class="text-sm text-gray-500 mb-2">Scroll to explore</span>
          <div class="w-6 h-10 border-2 border-gray-400 rounded-full flex justify-center p-1">
            <div class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce mt-1"></div>
          </div>
        </div>
      </div>
    </section>

    <!-- Features Section -->
    <section class="py-24 bg-white relative overflow-hidden" id="features">
      <div class="container mx-auto px-4">
        <div class="text-center mb-16 fade-in">
          <span class="inline-block px-3 py-1 bg-blue-100 text-blue-600 rounded-full text-sm font-semibold mb-4">
            Powerful Features
          </span>
          <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Innovative Learning <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Features</span></h2>
          <p class="text-xl text-gray-600 max-w-2xl mx-auto">
            Our platform combines cutting-edge technology with proven learning methodologies to deliver an exceptional educational experience.
          </p>
        </div>

        <!-- Background Decorations -->
        <div class="absolute top-40 right-0 -mr-16 hidden lg:block opacity-10">
          <svg width="400" height="400" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" class="text-blue-600">
            <path fill="currentColor" d="M47.1,-57.8C59.5,-47.8,67.6,-31.5,71.5,-14.1C75.4,3.3,75.2,21.8,67.1,35.7C59,49.5,43,58.8,26.1,65.3C9.2,71.8,-8.7,75.5,-24.9,70.7C-41.1,65.8,-55.6,52.3,-65.2,35.9C-74.8,19.4,-79.5,0,-74.3,-16.2C-69.1,-32.4,-54,-45.5,-38.7,-54.9C-23.4,-64.3,-7.8,-70,8.9,-79.8C25.6,-89.6,51.2,-103.5,67.7,-99.5C84.2,-95.5,91.5,-73.6,91.9,-53.3C92.3,-33,85.8,-14.3,77.2,1.5C68.6,17.3,57.9,30.2,46.2,41.6C34.5,53,21.8,62.9,6.2,68.5C-9.3,74.1,-27.8,75.5,-41.9,68.5C-56,61.5,-65.8,46.2,-71.7,30C-77.6,13.8,-79.6,-3.3,-76.2,-19.9C-72.8,-36.5,-64,-52.6,-50.9,-62.5C-37.8,-72.4,-20.4,-76.1,-2.9,-72.8C14.6,-69.5,32.2,-59.2,47.1,-57.8Z" transform="translate(100 100)" />
          </svg>
        </div>

        <div class="absolute bottom-20 left-0 -ml-16 hidden lg:block opacity-10">
          <svg width="300" height="300" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" class="text-indigo-600">
            <path fill="currentColor" d="M42.8,-62.2C54.9,-56.3,63.6,-42.8,69.7,-28.2C75.8,-13.6,79.3,2.1,76.3,16.9C73.3,31.7,63.8,45.6,51.1,55.6C38.4,65.7,22.4,71.9,5.2,75.1C-12,78.3,-30.4,78.4,-43.6,70.1C-56.8,61.8,-64.8,45,-69.9,28.1C-75,11.2,-77.2,-5.8,-73.2,-21.6C-69.2,-37.4,-59,-52,-45.6,-58.1C-32.2,-64.2,-15.7,-61.8,-0.1,-61.6C15.5,-61.5,30.7,-68.1,42.8,-62.2Z" transform="translate(100 100)" />
          </svg>
        </div>

        <div class="grid md:grid-cols-3 gap-8 relative z-10">
          <!-- Feature 1 -->
          <div class="bg-white rounded-xl shadow-lg p-8 border-t-4 border-blue-500 hover:shadow-xl transition duration-300 transform hover:-translate-y-2 scale-in" style="animation-delay: 0.1s">
            <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center mb-6 mx-auto text-white">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
              </svg>
            </div>
            <h3 class="text-xl font-semibold text-center text-gray-800 mb-3">AI-Generated Quizzes</h3>
            <p class="text-gray-600 text-center mb-4">
              Our platform uses advanced AI to automatically generate relevant quizzes from course content, providing personalized learning experiences.
            </p>
            <ul class="space-y-2 text-sm text-gray-600">
              <li class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-500 mr-2" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span>Content-based question generation</span>
              </li>
              <li class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-500 mr-2" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span>Adaptive difficulty levels</span>
              </li>
              <li class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-500 mr-2" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span>Multiple question formats</span>
              </li>
            </ul>
          </div>

          <!-- Feature 2 -->
          <div class="bg-white rounded-xl shadow-lg p-8 border-t-4 border-indigo-500 hover:shadow-xl transition duration-300 transform hover:-translate-y-2 scale-in" style="animation-delay: 0.3s">
            <div class="w-16 h-16 bg-gradient-to-br from-indigo-400 to-indigo-600 rounded-full flex items-center justify-center mb-6 mx-auto text-white">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
              </svg>
            </div>
            <h3 class="text-xl font-semibold text-center text-gray-800 mb-3">Secure Exam Environment</h3>
            <p class="text-gray-600 text-center mb-4">
              Take exams with confidence using our facial recognition technology that ensures academic integrity and prevents fraud.
            </p>
            <ul class="space-y-2 text-sm text-gray-600">
              <li class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-500 mr-2" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span>Facial recognition verification</span>
              </li>
              <li class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-500 mr-2" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span>Continuous identity monitoring</span>
              </li>
              <li class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-500 mr-2" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span>Tamper-proof exam sessions</span>
              </li>
            </ul>
          </div>

          <!-- Feature 3 -->
          <div class="bg-white rounded-xl shadow-lg p-8 border-t-4 border-purple-500 hover:shadow-xl transition duration-300 transform hover:-translate-y-2 scale-in" style="animation-delay: 0.5s">
            <div class="w-16 h-16 bg-gradient-to-br from-purple-400 to-purple-600 rounded-full flex items-center justify-center mb-6 mx-auto text-white">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
              </svg>
            </div>
            <h3 class="text-xl font-semibold text-center text-gray-800 mb-3">Adaptive Learning</h3>
            <p class="text-gray-600 text-center mb-4">
              Experience personalized learning paths that adapt to your strengths and weaknesses, optimizing your educational journey.
            </p>
            <ul class="space-y-2 text-sm text-gray-600">
              <li class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-500 mr-2" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span>Personalized learning paths</span>
              </li>
              <li class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-500 mr-2" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span>Real-time performance feedback</span>
              </li>
              <li class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-500 mr-2" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span>Targeted practice recommendations</span>
              </li>
            </ul>
          </div>
        </div>

        <!-- Additional Features -->
        <div class="mt-16 grid md:grid-cols-4 gap-6">
          <!-- Feature 4 -->
          <div class="bg-white rounded-lg p-6 shadow-md hover:shadow-lg transition duration-300 scale-in" style="animation-delay: 0.7s">
            <div class="flex items-center mb-4">
              <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                </svg>
              </div>
              <h4 class="font-semibold text-gray-800">Interactive Discussions</h4>
            </div>
            <p class="text-sm text-gray-600">
              Engage with peers and instructors through real-time discussion forums and collaborative learning spaces.
            </p>
          </div>

          <!-- Feature 5 -->
          <div class="bg-white rounded-lg p-6 shadow-md hover:shadow-lg transition duration-300 scale-in" style="animation-delay: 0.8s">
            <div class="flex items-center mb-4">
              <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <h4 class="font-semibold text-gray-800">Flexible Learning</h4>
            </div>
            <p class="text-sm text-gray-600">
              Learn at your own pace with on-demand access to course materials and recorded lectures.
            </p>
          </div>

          <!-- Feature 6 -->
          <div class="bg-white rounded-lg p-6 shadow-md hover:shadow-lg transition duration-300 scale-in" style="animation-delay: 0.9s">
            <div class="flex items-center mb-4">
              <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                </svg>
              </div>
              <h4 class="font-semibold text-gray-800">Certification</h4>
            </div>
            <p class="text-sm text-gray-600">
              Earn recognized certificates upon course completion to showcase your skills and knowledge.
            </p>
          </div>

          <!-- Feature 7 -->
          <div class="bg-white rounded-lg p-6 shadow-md hover:shadow-lg transition duration-300 scale-in" style="animation-delay: 1s">
            <div class="flex items-center mb-4">
              <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mr-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
              </div>
              <h4 class="font-semibold text-gray-800">Mobile Learning</h4>
            </div>
            <p class="text-sm text-gray-600">
              Access your courses anytime, anywhere with our responsive mobile-friendly platform.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- How It Works -->
    <section class="py-24 bg-blue-50 relative overflow-hidden" id="how-it-works">
      <div class="container mx-auto px-4">
        <div class="text-center mb-16 fade-in">
          <span class="inline-block px-3 py-1 bg-blue-100 text-blue-600 rounded-full text-sm font-semibold mb-4">
            Simple Process
          </span>
          <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">How <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">BrightPath</span> Works</h2>
          <p class="text-xl text-gray-600 max-w-2xl mx-auto">
            Our streamlined learning process makes it easy to start your educational journey and achieve your goals.
          </p>
        </div>

        <!-- Background Decorations -->
        <div class="absolute top-20 left-0 -ml-16 hidden lg:block opacity-10">
          <svg width="300" height="300" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" class="text-blue-600">
            <path fill="currentColor" d="M38.5,-65.3C52.9,-60.2,69.4,-54.3,79.3,-42.6C89.2,-30.9,92.4,-13.3,89.6,3C86.8,19.3,77.8,34.3,66.1,45.9C54.3,57.5,39.7,65.7,24.5,70.5C9.3,75.3,-6.4,76.7,-20.7,72.5C-35,68.3,-47.8,58.5,-58.4,46.4C-69,34.3,-77.4,19.9,-79.1,4.3C-80.8,-11.2,-75.9,-27.9,-66.1,-40.8C-56.3,-53.7,-41.6,-62.8,-27.5,-68.2C-13.4,-73.6,0.1,-75.3,12.4,-72.3C24.7,-69.3,36,-70.5,38.5,-65.3Z" transform="translate(100 100)" />
          </svg>
        </div>

        <!-- Process Steps with Connecting Lines -->
        <div class="relative">
          <!-- Connecting Line -->
          <div class="hidden md:block absolute top-24 left-0 right-0 h-1 bg-gradient-to-r from-blue-400 via-indigo-500 to-purple-600 transform translate-y-0.5 z-0"></div>

          <div class="grid md:grid-cols-4 gap-8 relative z-10">
            <!-- Step 1 -->
            <div class="flex flex-col items-center scale-in" style="animation-delay: 0.1s">
              <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-700 rounded-full flex items-center justify-center text-white text-2xl font-bold mb-6 shadow-lg pulse-slow">
                <span>1</span>
              </div>
              <h3 class="text-xl font-semibold text-center text-gray-800 mb-3">Register</h3>
              <p class="text-gray-600 text-center">Create your account and set up your personalized learning profile.</p>
              <div class="mt-4 flex justify-center">
                <a href="/register" class="text-blue-600 hover:text-blue-800 font-medium flex items-center text-sm">
                  <span>Get Started</span>
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                  </svg>
                </a>
              </div>
            </div>

            <!-- Step 2 -->
            <div class="flex flex-col items-center scale-in" style="animation-delay: 0.3s">
              <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-full flex items-center justify-center text-white text-2xl font-bold mb-6 shadow-lg pulse-slow">
                <span>2</span>
              </div>
              <h3 class="text-xl font-semibold text-center text-gray-800 mb-3">Explore Courses</h3>
              <p class="text-gray-600 text-center">Browse our extensive catalog of courses in various subjects and disciplines.</p>
              <div class="mt-4 flex justify-center">
                <a href="/courses" class="text-blue-600 hover:text-blue-800 font-medium flex items-center text-sm">
                  <span>View Courses</span>
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                  </svg>
                </a>
              </div>
            </div>

            <!-- Step 3 -->
            <div class="flex flex-col items-center scale-in" style="animation-delay: 0.5s">
              <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-700 rounded-full flex items-center justify-center text-white text-2xl font-bold mb-6 shadow-lg pulse-slow">
                <span>3</span>
              </div>
              <h3 class="text-xl font-semibold text-center text-gray-800 mb-3">Learn & Practice</h3>
              <p class="text-gray-600 text-center">Engage with interactive content and test your knowledge with AI-generated quizzes.</p>
              <div class="mt-4 flex justify-center">
                <a href="#features" class="text-blue-600 hover:text-blue-800 font-medium flex items-center text-sm">
                  <span>See Features</span>
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                  </svg>
                </a>
              </div>
            </div>

            <!-- Step 4 -->
            <div class="flex flex-col items-center scale-in" style="animation-delay: 0.7s">
              <div class="w-16 h-16 bg-gradient-to-br from-pink-500 to-pink-700 rounded-full flex items-center justify-center text-white text-2xl font-bold mb-6 shadow-lg pulse-slow">
                <span>4</span>
              </div>
              <h3 class="text-xl font-semibold text-center text-gray-800 mb-3">Get Certified</h3>
              <p class="text-gray-600 text-center">Complete secure exams and earn verified certificates to showcase your skills.</p>
              <div class="mt-4 flex justify-center">
                <a href="#testimonials" class="text-blue-600 hover:text-blue-800 font-medium flex items-center text-sm">
                  <span>Success Stories</span>
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                  </svg>
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- Additional Info -->
        <div class="mt-20 bg-white rounded-xl shadow-lg p-8 max-w-4xl mx-auto fade-in" style="animation-delay: 0.9s">
          <div class="flex flex-col md:flex-row items-center">
            <div class="md:w-1/3 mb-6 md:mb-0 flex justify-center">
              <div class="w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
              </div>
            </div>
            <div class="md:w-2/3 md:pl-8">
              <h3 class="text-xl font-semibold text-gray-800 mb-3">Start Your Learning Journey Today</h3>
              <p class="text-gray-600 mb-4">
                Join thousands of students who have already transformed their learning experience with BrightPath. Our platform is designed to make learning engaging, effective, and accessible to everyone.
              </p>
              <a href="/register" class="inline-block px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition duration-300 transform hover:-translate-y-1">
                <span class="flex items-center">
                  <span>Begin Your Journey</span>
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                  </svg>
                </span>
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Testimonials -->
    <section class="py-24 bg-white relative overflow-hidden" id="testimonials">
      <div class="container mx-auto px-4">
        <div class="text-center mb-16 fade-in">
          <span class="inline-block px-3 py-1 bg-blue-100 text-blue-600 rounded-full text-sm font-semibold mb-4">
            Success Stories
          </span>
          <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">What Our <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Students Say</span></h2>
          <p class="text-xl text-gray-600 max-w-2xl mx-auto">
            Discover how BrightPath has transformed the learning experience for students and educators worldwide.
          </p>
        </div>

        <!-- Background Decorations -->
        <div class="absolute top-40 right-0 -mr-16 hidden lg:block opacity-10">
          <svg width="300" height="300" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" class="text-indigo-600">
            <path fill="currentColor" d="M44.3,-76.2C58.4,-69.7,71.6,-59.5,79.3,-45.9C87,-32.3,89.3,-15.1,87.6,1.1C85.9,17.3,80.1,32.6,70.8,45.3C61.4,58,48.4,68.1,34.4,73.8C20.3,79.5,5.2,80.7,-9.7,78.5C-24.5,76.3,-39.1,70.6,-51.5,61.2C-63.9,51.8,-74.1,38.7,-79.3,23.8C-84.5,8.9,-84.7,-7.9,-80.8,-23.5C-76.9,-39.2,-68.9,-53.7,-56.9,-60.7C-44.9,-67.7,-28.9,-67.2,-13.7,-70.4C1.5,-73.5,16.7,-80.3,30.2,-82.7C43.7,-85.1,55.5,-83.1,44.3,-76.2Z" transform="translate(100 100)" />
          </svg>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
          <!-- Testimonial 1 -->
          <div class="bg-white rounded-xl shadow-xl p-8 relative border border-gray-100 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 scale-in" style="animation-delay: 0.1s">
            <!-- Quote Icon -->
            <div class="absolute -top-5 -left-5">
              <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
                </svg>
              </div>
            </div>

            <!-- Rating Stars -->
            <div class="flex mb-4">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
              </svg>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
              </svg>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
              </svg>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
              </svg>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
              </svg>
            </div>

            <!-- Testimonial Text -->
            <p class="text-gray-700 mb-6 relative">
              <span class="text-4xl text-blue-200 absolute -top-2 -left-2">"</span>
              BrightPath's AI-generated quizzes helped me identify my weak areas and focus my studies effectively. The platform is intuitive and engaging! I've improved my grades significantly since I started using it.
              <span class="text-4xl text-blue-200 absolute -bottom-6 -right-2">"</span>
            </p>

            <!-- User Info -->
            <div class="flex items-center mt-8">
              <img src="https://randomuser.me/api/portraits/women/32.jpg" alt="Sarah Johnson" class="w-14 h-14 rounded-full object-cover border-2 border-blue-100 shadow mr-4">
              <div>
                <h4 class="font-semibold text-gray-800">Sarah Johnson</h4>
                <div class="flex items-center">
                  <p class="text-blue-600 text-sm">Computer Science Student</p>
                  <span class="mx-2 text-gray-300">•</span>
                  <p class="text-gray-500 text-sm">Stanford University</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Testimonial 2 -->
          <div class="bg-white rounded-xl shadow-xl p-8 relative border border-gray-100 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 scale-in" style="animation-delay: 0.3s">
            <!-- Quote Icon -->
            <div class="absolute -top-5 -left-5">
              <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-full flex items-center justify-center shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
                </svg>
              </div>
            </div>

            <!-- Rating Stars -->
            <div class="flex mb-4">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
              </svg>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
              </svg>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
              </svg>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
              </svg>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
              </svg>
            </div>

            <!-- Testimonial Text -->
            <p class="text-gray-700 mb-6 relative">
              <span class="text-4xl text-indigo-200 absolute -top-2 -left-2">"</span>
              As an instructor, I appreciate the secure exam environment. The facial recognition feature ensures academic integrity while providing a seamless experience for students. It has revolutionized how I administer assessments.
              <span class="text-4xl text-indigo-200 absolute -bottom-6 -right-2">"</span>
            </p>

            <!-- User Info -->
            <div class="flex items-center mt-8">
              <img src="https://randomuser.me/api/portraits/men/42.jpg" alt="Dr. Michael Chen" class="w-14 h-14 rounded-full object-cover border-2 border-indigo-100 shadow mr-4">
              <div>
                <h4 class="font-semibold text-gray-800">Dr. Michael Chen</h4>
                <div class="flex items-center">
                  <p class="text-indigo-600 text-sm">Professor of Mathematics</p>
                  <span class="mx-2 text-gray-300">•</span>
                  <p class="text-gray-500 text-sm">MIT</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Testimonial 3 -->
          <div class="bg-white rounded-xl shadow-xl p-8 relative border border-gray-100 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 scale-in" style="animation-delay: 0.5s">
            <!-- Quote Icon -->
            <div class="absolute -top-5 -left-5">
              <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
                </svg>
              </div>
            </div>

            <!-- Rating Stars -->
            <div class="flex mb-4">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
              </svg>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
              </svg>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
              </svg>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
              </svg>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
              </svg>
            </div>

            <!-- Testimonial Text -->
            <p class="text-gray-700 mb-6 relative">
              <span class="text-4xl text-purple-200 absolute -top-2 -left-2">"</span>
              The detailed analytics have transformed how I approach my studies. I can see my progress in real-time and focus on areas where I need improvement. The adaptive learning system feels like having a personal tutor guiding me every step of the way.
              <span class="text-4xl text-purple-200 absolute -bottom-6 -right-2">"</span>
            </p>

            <!-- User Info -->
            <div class="flex items-center mt-8">
              <img src="https://randomuser.me/api/portraits/men/22.jpg" alt="Alex Rodriguez" class="w-14 h-14 rounded-full object-cover border-2 border-purple-100 shadow mr-4">
              <div>
                <h4 class="font-semibold text-gray-800">Alex Rodriguez</h4>
                <div class="flex items-center">
                  <p class="text-purple-600 text-sm">Business Administration Student</p>
                  <span class="mx-2 text-gray-300">•</span>
                  <p class="text-gray-500 text-sm">Harvard Business School</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- View More Testimonials Button -->
        <div class="text-center mt-12 fade-in" style="animation-delay: 0.7s">
          <a href="#" class="inline-flex items-center px-6 py-3 border border-blue-300 text-base font-medium rounded-md text-blue-700 bg-white hover:bg-blue-50 transition duration-300">
            <span>View More Success Stories</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
          </a>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="py-24 bg-gradient-to-r from-blue-600 to-indigo-700 relative overflow-hidden" id="get-started">
      <!-- Background Elements -->
      <div class="absolute inset-0 z-0 overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-full opacity-10">
          <svg width="100%" height="100%" viewBox="0 0 100 100" preserveAspectRatio="none">
            <defs>
              <pattern id="grid" width="8" height="8" patternUnits="userSpaceOnUse">
                <path d="M 8 0 L 0 0 0 8" fill="none" stroke="white" stroke-width="0.5" />
              </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#grid)" />
          </svg>
        </div>

        <div class="absolute -top-24 -right-24 w-64 h-64 bg-white rounded-full opacity-10 blur-3xl"></div>
        <div class="absolute -bottom-32 -left-32 w-80 h-80 bg-white rounded-full opacity-10 blur-3xl"></div>
      </div>

      <div class="container mx-auto px-4 text-center relative z-10">
        <div class="max-w-4xl mx-auto fade-in">
          <span class="inline-block px-3 py-1 bg-white bg-opacity-20 text-white rounded-full text-sm font-semibold mb-4">
            Start Your Journey
          </span>
          <h2 class="text-3xl md:text-5xl font-bold text-white mb-6 leading-tight">Ready to Transform Your <span class="text-blue-200">Learning Experience?</span></h2>
          <p class="text-xl text-blue-100 mb-10 max-w-2xl mx-auto">
            Join thousands of students who are already benefiting from our innovative learning platform. Start your journey to academic success today.
          </p>

          <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-4">
            <a href="/register" class="px-8 py-4 bg-white text-blue-600 font-bold rounded-lg shadow-lg hover:bg-blue-50 transition duration-300 transform hover:-translate-y-1 inline-flex items-center scale-in" style="animation-delay: 0.2s">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
              </svg>
              Create Free Account
            </a>
            <a href="/courses" class="px-8 py-4 bg-transparent text-white font-bold rounded-lg border-2 border-white hover:bg-white hover:bg-opacity-10 transition duration-300 transform hover:-translate-y-1 inline-flex items-center scale-in" style="animation-delay: 0.4s">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z" />
              </svg>
              Explore Courses
            </a>
          </div>

          <!-- Trust Badges -->
          <div class="mt-16 flex flex-wrap justify-center items-center gap-8 opacity-80">
            <div class="flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
              </svg>
              <span class="text-white">Secure Platform</span>
            </div>
            <div class="flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span class="text-white">24/7 Access</span>
            </div>
            <div class="flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
              </svg>
              <span class="text-white">AI-Powered</span>
            </div>
            <div class="flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
              <span class="text-white">Community Support</span>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <!-- Footer -->
  <?php echo $__env->make('components.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</body>
</html>
<?php /**PATH /home/karim/Plateforme-de-Formation-en-Ligne-Interactive/resources/views/public/welcome.blade.php ENDPATH**/ ?>