/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)



// const $ = require('jquery');
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything


// or you can include specific pieces
// require('bootstrap/js/dist/tooltip');
// require('bootstrap/js/dist/popover');

// $(document).ready(function() {
//     $('[data-toggle="popover"]').popover();
// });



//JS file



const $ = require('jquery');

// import 'glightbox';

// create global $ and jQuery variables
global.$ = global.jQuery = $;

import 'bootstrap';

import 'select2';
// require('bootstrap/dist/js/bootstrap.bundle');
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min';
// import 'nanogallery2';
// import './admin/js/sb-admin-2.min.js';



// import GLightbox from 'glightbox';



// Import nanogallery2 JS
// import 'nanogallery2/dist/css/nanogallery2.min.css';
{/* <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
<link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
<link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
<link href="assets/vendor/aos/aos.css" rel="stylesheet"> */}

// <!-- Template Main CSS File -->
{/* <link href="assets/css/main.css" rel="stylesheet"> */}
import './admin/vendor/fontawesome-free/css/all.css';
import './front/vendor/bootstrap-icons/bootstrap-icons.css';
// import './front/vendor/swiper/swiper-bundle.min.css';
// import './front/vendor/glightbox/css/glightbox.min.css';
// import './front/vendor/aos/aos.css';
// import './front/css/main.css';
import './styles/app.css';






