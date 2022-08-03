/*
	Graphictoria 5 (https://gtoria.net)
	Copyright © XlXi 2022
*/

import $ from 'jquery';

import React from 'react';
import { render } from 'react-dom';

import Feed from '../components/Feed';

const feedId = 'gt-dash-feed';

$(document).ready(function() {
	if (document.getElementById(feedId)) {
		render(<Feed />, document.getElementById(feedId));
	}
});