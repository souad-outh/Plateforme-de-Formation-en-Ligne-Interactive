/**
 * Face Recognition System for Secure Exams
 * 
 * This script handles face registration and verification for the secure exam system.
 */

// Global variables
let isModelLoaded = false;
let labeledFaceDescriptors = [];
let faceMatcher = null;
let registeredFaceDescriptor = null;
let verificationInterval = null;
let lastVerificationResult = null;
let verificationCount = 0;
let failedVerifications = 0;

// DOM elements
const videoEl = document.getElementById('face-video');
const canvasEl = document.getElementById('face-canvas');
const statusEl = document.getElementById('face-status');
const captureBtn = document.getElementById('capture-btn');
const verifyBtn = document.getElementById('verify-btn');
const registerBtn = document.getElementById('register-btn');
const progressEl = document.getElementById('face-progress');

/**
 * Initialize the face recognition system
 */
async function initFaceRecognition() {
    try {
        updateStatus('Loading face recognition models...', 'info');
        
        // Load required face-api.js models
        await faceapi.nets.ssdMobilenetv1.loadFromUri('/js/face-api/models');
        await faceapi.nets.faceLandmark68Net.loadFromUri('/js/face-api/models');
        await faceapi.nets.faceRecognitionNet.loadFromUri('/js/face-api/models');
        await faceapi.nets.faceExpressionNet.loadFromUri('/js/face-api/models');
        
        isModelLoaded = true;
        updateStatus('Face recognition models loaded successfully', 'success');
        
        // Load previously registered face if available
        loadRegisteredFace();
        
        return true;
    } catch (error) {
        console.error('Error initializing face recognition:', error);
        updateStatus('Failed to load face recognition models: ' + error.message, 'error');
        return false;
    }
}

/**
 * Start the webcam stream
 */
async function startVideo() {
    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        updateStatus('Your browser does not support webcam access', 'error');
        return false;
    }
    
    try {
        const stream = await navigator.mediaDevices.getUserMedia({ 
            video: { 
                width: 640,
                height: 480,
                facingMode: 'user'
            } 
        });
        
        videoEl.srcObject = stream;
        updateStatus('Camera started successfully', 'success');
        return true;
    } catch (error) {
        console.error('Error starting video:', error);
        updateStatus('Failed to access webcam: ' + error.message, 'error');
        return false;
    }
}

/**
 * Stop the webcam stream
 */
function stopVideo() {
    if (videoEl.srcObject) {
        const tracks = videoEl.srcObject.getTracks();
        tracks.forEach(track => track.stop());
        videoEl.srcObject = null;
    }
}

/**
 * Capture a face from the video stream
 */
async function captureFace() {
    if (!isModelLoaded || !videoEl.srcObject) {
        updateStatus('Face recognition not initialized or camera not started', 'error');
        return null;
    }
    
    try {
        updateStatus('Detecting face...', 'info');
        
        // Detect face with landmarks and descriptor
        const detection = await faceapi.detectSingleFace(videoEl)
            .withFaceLandmarks()
            .withFaceDescriptor();
            
        if (!detection) {
            updateStatus('No face detected. Please ensure your face is clearly visible', 'warning');
            return null;
        }
        
        // Draw detection on canvas
        const dims = faceapi.matchDimensions(canvasEl, videoEl);
        const resizedDetection = faceapi.resizeResults(detection, dims);
        
        canvasEl.getContext('2d').clearRect(0, 0, canvasEl.width, canvasEl.height);
        faceapi.draw.drawDetections(canvasEl, resizedDetection);
        faceapi.draw.drawFaceLandmarks(canvasEl, resizedDetection);
        
        updateStatus('Face captured successfully', 'success');
        return detection.descriptor;
    } catch (error) {
        console.error('Error capturing face:', error);
        updateStatus('Error capturing face: ' + error.message, 'error');
        return null;
    }
}

/**
 * Register a face for future verification
 */
async function registerFace() {
    const descriptor = await captureFace();
    
    if (!descriptor) {
        return false;
    }
    
    try {
        // Save the face descriptor
        registeredFaceDescriptor = Array.from(descriptor);
        
        // Store in localStorage (in a real app, this would be sent to the server)
        localStorage.setItem('registeredFace', JSON.stringify(registeredFaceDescriptor));
        
        // Create labeled face descriptor and face matcher
        const labeledDescriptor = new faceapi.LabeledFaceDescriptors(
            'user', 
            [new Float32Array(registeredFaceDescriptor)]
        );
        
        labeledFaceDescriptors = [labeledDescriptor];
        faceMatcher = faceapi.createMatcher(labeledFaceDescriptors, 0.6);
        
        updateStatus('Face registered successfully', 'success');
        return true;
    } catch (error) {
        console.error('Error registering face:', error);
        updateStatus('Error registering face: ' + error.message, 'error');
        return false;
    }
}

/**
 * Load a previously registered face
 */
function loadRegisteredFace() {
    try {
        const savedFace = localStorage.getItem('registeredFace');
        
        if (savedFace) {
            registeredFaceDescriptor = JSON.parse(savedFace);
            
            // Create labeled face descriptor and face matcher
            const labeledDescriptor = new faceapi.LabeledFaceDescriptors(
                'user', 
                [new Float32Array(registeredFaceDescriptor)]
            );
            
            labeledFaceDescriptors = [labeledDescriptor];
            faceMatcher = faceapi.createMatcher(labeledFaceDescriptors, 0.6);
            
            updateStatus('Loaded registered face', 'info');
            return true;
        }
        
        return false;
    } catch (error) {
        console.error('Error loading registered face:', error);
        return false;
    }
}

/**
 * Verify a face against the registered face
 */
async function verifyFace() {
    if (!faceMatcher) {
        updateStatus('No registered face to verify against', 'error');
        return false;
    }
    
    const descriptor = await captureFace();
    
    if (!descriptor) {
        return false;
    }
    
    try {
        // Match the captured face against the registered face
        const match = faceMatcher.findBestMatch(descriptor);
        
        if (match.label === 'unknown') {
            updateStatus('Verification failed: Face does not match registered user', 'error');
            return false;
        }
        
        const distance = match.distance;
        updateStatus(`Verification successful (confidence: ${((1 - distance) * 100).toFixed(2)}%)`, 'success');
        return true;
    } catch (error) {
        console.error('Error verifying face:', error);
        updateStatus('Error verifying face: ' + error.message, 'error');
        return false;
    }
}

/**
 * Start continuous verification for exam monitoring
 */
function startVerification(intervalSeconds = 30, maxFailures = 3) {
    if (verificationInterval) {
        clearInterval(verificationInterval);
    }
    
    verificationCount = 0;
    failedVerifications = 0;
    
    updateStatus('Starting verification...', 'info');
    
    // Perform initial verification
    performVerification();
    
    // Set up interval for periodic verification
    verificationInterval = setInterval(performVerification, intervalSeconds * 1000);
    
    async function performVerification() {
        verificationCount++;
        updateStatus(`Performing verification check #${verificationCount}...`, 'info');
        
        const result = await verifyFace();
        lastVerificationResult = result;
        
        if (!result) {
            failedVerifications++;
            updateStatus(`Verification failed (${failedVerifications}/${maxFailures})`, 'error');
            
            if (failedVerifications >= maxFailures) {
                stopVerification();
                updateStatus('Too many failed verifications. Exam session terminated.', 'error');
                
                // In a real implementation, this would notify the server and end the exam
                document.dispatchEvent(new CustomEvent('exam-terminated', { 
                    detail: { reason: 'verification-failure' } 
                }));
            }
        } else {
            updateStatus('Verification successful', 'success');
        }
    }
}

/**
 * Stop continuous verification
 */
function stopVerification() {
    if (verificationInterval) {
        clearInterval(verificationInterval);
        verificationInterval = null;
    }
}

/**
 * Update the status message
 */
function updateStatus(message, type = 'info') {
    if (!statusEl) return;
    
    statusEl.textContent = message;
    statusEl.className = '';
    statusEl.classList.add(`status-${type}`);
    
    console.log(`[Face Recognition] ${message}`);
}

// Export functions for use in other scripts
window.faceRecognition = {
    init: initFaceRecognition,
    startVideo,
    stopVideo,
    captureFace,
    registerFace,
    verifyFace,
    startVerification,
    stopVerification,
    isRegistered: () => !!registeredFaceDescriptor
};
