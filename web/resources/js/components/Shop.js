/*
	Copyright © XlXi 2022
*/

import { Component } from 'react';

import classNames from 'classnames/bind';

import axios from 'axios';

import Twemoji from 'react-twemoji';

import { buildGenericApiUrl } from '../util/HTTP.js';
import ProgressiveImage from './ProgressiveImage';
import Loader from './Loader';

axios.defaults.withCredentials = true;

const shopCategories = {
	'Clothing': [
		{
			label: 'Hats',
			assetTypeId: 8
		},
		{
			label: 'Shirts',
			assetTypeId: 11
		},
		{
			label: 'T-Shirts',
			assetTypeId: 2
		},
		{
			label: 'Pants',
			assetTypeId: 12
		},
		{
			label: 'Package',
			assetTypeId: 32
		}
	],
	'Body Parts': [
		{
			label: 'Heads',
			assetTypeId: 17
		},
		{
			label: 'Faces',
			assetTypeId: 18
		},
		{
			label: 'Packages',
			assetTypeId: 32
		}
	],
	'Gear': [
		{
			label: 'Building',
			assetTypeId: 19,
			gearGenreId: 7
		},
		{
			label: 'Explosive',
			assetTypeId: 19,
			gearGenreId: 2
		},
		{
			label: 'Melee',
			assetTypeId: 19,
			gearGenreId: 0
		},
		{
			label: 'Musical',
			assetTypeId: 19,
			gearGenreId: 5
		},
		{
			label: 'Navigation',
			assetTypeId: 19,
			gearGenreId: 4
		},
		{
			label: 'Power Up',
			assetTypeId: 19,
			gearGenreId: 3
		},
		{
			label: 'Ranged',
			assetTypeId: 19,
			gearGenreId: 1
		},
		{
			label: 'Social',
			assetTypeId: 19,
			gearGenreId: 6
		},
		{
			label: 'Transport',
			assetTypeId: 19,
			gearGenreId: 8
		}
	]
};

function makeCategoryId(originalName, category) {
	return `shop-${originalName.toLowerCase().replaceAll(' ', '-')}-${category}`;
}

function commaSeparate(num) {
	let str = num.toString().split('.');
	str[0] = str[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
	return str.join('.');
}

class ShopCategoryButton extends Component {
	constructor(props) {
		super(props);
		
		this.handleClick = this.handleClick.bind(this);
	}
	
	componentDidMount() {
		if (this.props.id === 'all') {
			let assetTypes = [];
			
			Object.keys(shopCategories).map((categoryName) => {
				let categoryAssetTypeIds = this.props.getCategoryAssetTypeIds(categoryName);
				
				switch(typeof(categoryAssetTypeIds.assetTypeId)) {
					case 'string':
					case 'number':
						assetTypes[categoryAssetTypeIds.assetTypeId] = true;
						break;
					case 'object':
						categoryAssetTypeIds.assetTypeId.map((assetTypeId) => {
							assetTypes[assetTypeId] = true;
						});
						break;
				}
			});
			
			this.data = {assetTypeId: Object.keys(assetTypes)};
		} else if (this.props.id.startsWith('shop-all')) {
			this.data = this.props.getCategoryAssetTypeIds(this.props.categoryName);
		} else {
			this.data = this.props.getCategoryAssetTypeByLabel(this.props.categoryName, this.props.label);
		}
		
		if (this.props.id == 'shop-hats-clothing-type')
			this.props.navigateCategory(this.props.id, this.data);
	}
	
	handleClick() {
		this.props.setPage(1, true, () => {
			this.props.navigateCategory(this.props.id, this.data);
		});
	}
	
	render() {
		return (
			<a href="#"
			className={classNames({
				'text-decoration-none': true,
				'ms-2': (this.props.id != 'all'),
				'fw-bold': (this.props.shopState.selectedCategoryId == this.props.id)
			})}
			onClick={this.handleClick}>
				{ this.props.label }
			</a>
		);
	}
}

class ShopCategories extends Component {
	constructor(props) {
		super(props);
	}
	
	render() {
		return (
			<div className="virtubrick-shop-categories">
				<h5>Category</h5>
				<ShopCategoryButton id="all" label="All Items" getCategoryAssetTypeByLabel={this.props.getCategoryAssetTypeByLabel} getCategoryAssetTypeIds={this.props.getCategoryAssetTypeIds} navigateCategory={this.props.navigateCategory} shopState={this.props.shopState} setPage={this.props.setPage} />
				<ul className="list-unstyled ps-0">
					{
						Object.keys(shopCategories).map((categoryName, index) =>
							<li className="mb-1">
								<a className="text-decoration-none fw-normal align-items-center virtubrick-list-dropdown" data-bs-toggle="collapse" data-bs-target={`#${makeCategoryId(categoryName, 'collapse')}`} aria-expanded={(index === 0 ? 'true' : 'false')} href="#">{ categoryName }</a>
								<div className={classNames({'collapse': true, 'show': (index === 0)})} id={makeCategoryId(categoryName, 'collapse')}>
									<ul className="btn-toggle-nav list-unstyled fw-normal small">
										<li><ShopCategoryButton id={makeCategoryId(`all-${categoryName}`, 'type')} label={`All ${categoryName}`} categoryName={categoryName} getCategoryAssetTypeByLabel={this.props.getCategoryAssetTypeByLabel} getCategoryAssetTypeIds={this.props.getCategoryAssetTypeIds} navigateCategory={this.props.navigateCategory} shopState={this.props.shopState} setPage={this.props.setPage} /></li>
										{
											shopCategories[categoryName].map(({label, assetTypeId, gearGenreId}, index) =>
												<li><ShopCategoryButton id={makeCategoryId(`${label}-${categoryName}`, 'type')} label={label} categoryName={categoryName} getCategoryAssetTypeByLabel={this.props.getCategoryAssetTypeByLabel} getCategoryAssetTypeIds={this.props.getCategoryAssetTypeIds} navigateCategory={this.props.navigateCategory} shopState={this.props.shopState} setPage={this.props.setPage} /></li>
											)
										}
									</ul>
								</div>
							</li>
						)
					}
				</ul>
			</div>
		);
	}
}

class ShopItemCard extends Component {
	constructor(props) {
		super(props);
		this.state = {
			hovered: false
		}
	}
	
	render() {
		var item = this.props.item;
		
		return (
			<a
				className="virtubrick-item-card"
				href={ item.Url }
				onMouseEnter={() => this.setState({hovered: true})}
				onMouseLeave={() => this.setState({hovered: false})}
			>
				<span className="card m-2">
					<ProgressiveImage
						src={ item.Thumbnail } 
						placeholderImg={ buildGenericApiUrl('www', 'images/busy/asset.png') }
						alt={ item.Name }
						className='img-fluid'
					/>
					<div className="p-2">
						<p className="text-truncate">{ item.Name }</p>
						{ item.OnSale ?
						<p className="virtubrick-tokens text-truncate">{commaSeparate(item.Price)}</p>
						: <p className="text-muted">Offsale</p>
						}
					</div>
				</span>
				{
					this.state.hovered ?
					<span className="virtubrick-item-details">
						<div className="card px-2">
							<p className="text-truncate">
								<span className="text-muted">Creator: </span><a href={ item.Creator.Url } className="text-decoration-none fw-normal">{ item.Creator.Name }</a>
							</p>
							{/* TODO: XlXi: These */}
							<p className="text-truncate">
								<span className="text-muted">Updated: </span>test
							</p>
							<p className="text-truncate">
								<span className="text-muted">Sales: </span>test
							</p>
							<p className="text-truncate">
								<span className="text-muted">Favorites: </span>test
							</p>
						</div>
					</span>
					:
					null
				}
			</a>
		);
	}
}

class Shop extends Component {
	constructor(props) {
		super(props);
		this.state = {
			selectedCategoryId: -1,
			pageKey: 0,
			pageItems: [],
			pageLoaded: true,
			pageNumber: null,
			pageCount: null,
			error: false,
			dataMem: false
		};
		
		this.navigateCategory = this.navigateCategory.bind(this);
		this.incrementPage = this.incrementPage.bind(this);
		this.setPage = this.setPage.bind(this);
	}
	
	getCategoryAssetTypeIds(categoryName) {
		let assetTypes = [];
		
		shopCategories[categoryName].map(({assetTypeId}) => {
			assetTypes[assetTypeId] = true;
		});
		
		assetTypes = Object.keys(assetTypes);
		
		if (assetTypes.length == 1)
			assetTypes = assetTypes[0];
		
		return {assetTypeId: assetTypes};
	}
	
	getCategoryAssetTypeByLabel(categoryName, label) {
		let assetType = -1;
		
		shopCategories[categoryName].map((sort) => {
			if (sort.label === label) {
				assetType = sort;
			}
		});
		
		return assetType;
	}
	
	navigateCategory(categoryId, dataraw) {
		if(this.state.pageLoaded == false) return;
		
		this.setState({selectedCategoryId: categoryId, dataMem: dataraw, pageLoaded: false});
		
		let url = buildGenericApiUrl('api', 'shop/v1/list-json');
		
		if (this.state.pageNumber == null || this.state.pageNumber == 1)
			this.setState({pageNumber: 1});
		
		let paramIterator = 0;
		let data = {...dataraw, page: this.state.pageNumber};
		Object.keys(data).filter(key => {
			if (key == 'label')
				return false;
			else if (key == 'page' && (data[key] == null || data[key] == 1))
				return false;
			return true;
		}).map(key => {
			url += ((paramIterator++ == 0 ? '?' : '&') + `${key}=${data[key]}`);
		});
		
		let newKey = this.state.pageKey + 1;
		axios.get(url)
			.then(res => {
				const items = res.data;
				
				this.setState({ pageKey: newKey, pageItems: items.data, pageCount: items.pages, pageLoaded: true, error: false });
			}).catch(err => {
				const data = err.response.data;
				
				let errorMessage = 'An error occurred while processing your request.';
				if(data.errors)
					errorMessage = data.errors[0].message;
				
				this.setState({ pageKey: newKey, pageItems: [], pageCount: 1, pageNumber: 1, pageLoaded: true, error: errorMessage });
				this.inputBox.current.focus();
			});
	}
	
	incrementPage(amount) {
		this.setPage(this.state.pageNumber + amount);
	}
	
	setPage(page, bypass = false, callback) {
		if(!bypass && this.state.pageLoaded == false) return;
		
		this.setState({pageNumber: page}, () => {
			if(callback)
				callback();
			
			if(!bypass)
				this.navigateCategory(this.state.selectedCategoryId, this.state.dataMem);
		});
	}
	
	render() {
		return (
			<div className="container-lg my-2">
				<div className="row">
					<div className="col d-flex">
						<h4 className="my-auto">Shop</h4>
					</div>
					<div className="col-lg-5 my-2 my-lg-0 mb-lg-2 d-flex">
						<button className="btn btn-secondary me-2"><i className="fa-solid fa-gear"></i></button>
						<div className="input-group">
							<input type="text" className="form-control d-lg-flex" placeholder="Item name" />
							<button className="btn btn-primary">Search</button>
						</div>
					</div>
				</div>
				<div className="row">
					<div className="col-md-2">
						<ShopCategories getCategoryAssetTypeByLabel={this.getCategoryAssetTypeByLabel} getCategoryAssetTypeIds={this.getCategoryAssetTypeIds} navigateCategory={this.navigateCategory} shopState={this.state} setPage={this.setPage} />
					</div>
					<div className="col-md-10 d-flex flex-column">
						<div className="card p-3">
							{
								this.state.error ?
								<div className="alert alert-danger p-2 mb-0 text-center">{this.state.error}</div>
								:
								null
							}
							{
								!this.state.pageLoaded ?
								<div className="virtubrick-shop-overlay">
									<Loader />
								</div>
								:
								null
							}
							{
								(this.state.pageItems.length == 0 && !this.state.error) ?
								<p className="text-muted text-center">Nothing found.</p>
								:
								<div key={ this.state.pageKey }>
									{
										this.state.pageItems.map((item, index) =>
											<ShopItemCard item={ item } key={ index } />
										)
									}
								</div>
							}
						</div>
						{
							this.state.pageCount > 1 ?
							<ul className="list-inline mx-auto mt-3">
								<li className="list-inline-item">
									<button className="btn btn-secondary" disabled={(this.state.pageNumber <= 1) ? true : null} onClick={ () => this.incrementPage(-1) }><i className="fa-solid fa-angle-left"></i></button>
								</li>
								<li className="list-inline-item virtubrick-paginator">
									<span>Page&nbsp;</span>
									<input type="text" value={ this.state.pageNumber || '' } className="form-control" disabled={this.state.pageLoaded ? null : true} />
									<span>&nbsp;of { this.state.pageCount || '???' }</span>
								</li>
								<li className="list-inline-item">
									<button className="btn btn-secondary" disabled={(this.state.pageNumber >= this.state.pageCount) ? true : null} onClick={ () => this.incrementPage(1) }><i className="fa-solid fa-angle-right"></i></button>
								</li>
							</ul>
							:
							null
						}
					</div>
				</div>
			</div>
		);
	}
}

export default Shop;