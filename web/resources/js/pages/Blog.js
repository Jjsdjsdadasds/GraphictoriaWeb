/*
	Copyright © XlXi 2022
*/

import $ from 'jquery';
import * as Bootstrap from 'bootstrap';
import React from 'react';

const navId = 'vb-blog-nav';
const hideClass = 'virtubrick-blognav-hide';

function scrollChanged() {
	const nav = document.getElementById(navId);
	const scr = document.documentElement.scrollTop;
	
	if(scr < Math.max(120, 330 - nav.offsetHeight)) {
		if(!nav.classList.contains(hideClass))
			nav.classList.add(hideClass);
	}
	else
	{
		if(nav.classList.contains(hideClass))
			nav.classList.remove(hideClass);
	}
}

$(document).ready(function() {
	if (document.getElementById(navId)) {
		window.addEventListener('scroll', scrollChanged);
		scrollChanged();
	}
});