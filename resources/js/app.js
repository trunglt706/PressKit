import './bootstrap';
import lightGallery from 'lightgallery';
import 'lightgallery/css/lightgallery.css';

document.addEventListener('DOMContentLoaded', function () {
	const galleryContainers = document.querySelectorAll('[data-lightgallery]');

	galleryContainers.forEach(function (container) {
		lightGallery(container, {
			selector: '.js-lg-item',
			download: false,
			counter: true,
		});
	});
});
