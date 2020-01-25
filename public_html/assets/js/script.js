'use strict';

/**
 * Галерея. Good
 */
// Стандартные
const images = {
	element: null,
	btnClass: null,
	oldImageEl: null,
	newImageEl: null,

	settings: {
		previewSelector: '.galleryPreviewsContainer',
		btnClass: '.gallery-btn',
		imageBackBtnId: '#angelBtn-left',
		imageNextBtnId: '#angelBtn-right',
	},

	/**
	 * Инициализирует галерею, ставит обработчик события.
	 * @param {Object} settings Объект настроек для галереи.
	 */
	init(settings) {
		// Записываем настройки, которые передал пользователь в наши настройки.
		this.settings = Object.assign(this.settings, settings);
		// Находим элемент, где будет картинка.
		this.btnClass = document.querySelector(this.settings.btnClass);
		this.element = document.querySelector(this.settings.previewSelector);
		this.newImageEl = this.element.firstElementChild;
		this.createBtn();
	},

	hideImage(oldImageEl) {
		oldImageEl.style.display = 'none';
	},

	showImage(newImageEl) {
		newImageEl.style.display = 'block';
	},

	createBtn() {
		// Добавляем кнопку назад.
		const backBtn = document.querySelector(this.settings.imageBackBtnId);
		// Добавляем обработчик события при клике, ставим новую открытую картинку и открываем ее.
		backBtn.addEventListener('click', (e) => {
			this.oldImageEl = this.newImageEl;
			this.newImageEl = this.getPrevImage();
			this.hideImage(this.oldImageEl);
			this.showImage(this.newImageEl);
			e.preventDefault();
		});

		// Добавляем кнопку вперед.
		const nextBtn = document.querySelector(this.settings.imageNextBtnId);

		// Добавляем обработчик события при клике, ставим новую открытую картинку и открываем ее.
		nextBtn.addEventListener('click', (e) => {
			this.oldImageEl = this.newImageEl;
			this.newImageEl = this.getNextImage();
			this.hideImage(this.oldImageEl);
			this.showImage(this.newImageEl)
			e.preventDefault();
		});
	},

	getNextImage() {
		// Получаем элемент справа от текущей открытой картинки.
		const nextSibling = this.oldImageEl.nextElementSibling;
		// Если элемент справа есть, его отдаем, если нет, то берем первый элемент в родительском контейнере.
		return nextSibling ? nextSibling : this.element.firstElementChild;
	},

	getPrevImage() {
		// Получаем элемент слева от текущей открытой картинки.
		const prevSibling = this.oldImageEl.previousElementSibling;
		// Если элемент слева есть, его отдаем, если нет, то берем последний элемент в родительском контейнере.
		return prevSibling ? prevSibling : this.element.lastElementChild;
	}
};
// Увеличенные
const gallery = {
	openedImageEl: null,

	settings: {
		previewSelector: '.galleryPreviewsContainer',
		openedImageWrapperClass: 'galleryWrapper',
		openedImageClass: 'galleryWrapper__image',
		openedImageScreenClass: 'galleryWrapper__screen',
		openedImageCloseBtnClass: 'galleryWrapper__close',
		openedImageCloseBtnSrc: 'assets/img/icons/button/close-button.png',
		openedImageNextBtnSrc: 'assets/img/icons/button/next-button.png',
		openedImageNextBtnId: 'galleryWrapper__next',
		openedImageBackBtnSrc: 'assets/img/icons/button/back-button.png',
		openedImageBackBtnId: 'galleryWrapper__back',
		imageNotFoundSrc: 'images/gallery/duck.gif',
	},

	/**
	 * Инициализирует галерею, ставит обработчик события.
	 * @param {Object} settings Объект настроек для галереи.
	 */
	init(settings) {
		// Записываем настройки, которые передал пользователь в наши настройки.
		this.settings = Object.assign(this.settings, settings);
		const element = document.querySelector(this.settings.previewSelector);

		// Находим элемент, где будут превью картинок и ставим обработчик на этот элемент,
		// при клике на этот элемент вызовем функцию containerClickHandler в нашем объекте
		// gallery и передадим туда событие MouseEvent, которое случилось.
		element.addEventListener('click', event => this.containerClickHandler(event));
	},

	/**
	 * Обработчик события клика для открытия картинки.
	 * @param {MouseEvent} event Событие клики мышью.
	 * @param {HTMLElement} event.target Событие клики мышью.
	 */
	containerClickHandler(event) {
		// Если целевой тег не был картинкой, то ничего не делаем, просто завершаем функцию.
		if (event.target.tagName !== 'IMG') {
			return;
		}

		// Записываем текущую картинку, которую хотим открыть.
		this.openedImageEl = event.target;

		// Открываем картинку.
		this.openImage(event.target.dataset.full_image_url);
	},

	/**
	 * Открывает картинку.
	 * @param {string} src Ссылка на картинку, которую надо открыть.
	 */
	openImage(src) {
		// Пробуем загрузить картинку, если картинка загружена - показываем картинку с полученным из
		// целевого тега (data-full_image_url аттрибут), если картинка не загрузилась - показываем картинку-заглушку.
		// Получаем контейнер для открытой картинки, в нем находим тег img и ставим ему нужный src.
		const openedImageEl = this.getScreenContainer().querySelector(`.${this.settings.openedImageClass}`);
		const img = new Image();
		img.onload = () => openedImageEl.src = src;
		img.onerror = () => openedImageEl.src = this.settings.imageNotFoundSrc;
		img.src = src;
	},

	/**
	 * Возвращает контейнер для открытой картинки, либо создает такой контейнер, если его еще нет.
	 * @returns {Element}
	 */
	getScreenContainer() {
		// Получаем контейнер для открытой картинки.
		const galleryWrapperElement = document.querySelector(`.${this.settings.openedImageWrapperClass}`);
		// Если контейнер для открытой картинки существует - возвращаем его.
		if (galleryWrapperElement) {
			return galleryWrapperElement;
		}

		// Возвращаем полученный из метода createScreenContainer контейнер.
		return this.createScreenContainer();
	},

	/**
	 * Создает контейнер для открытой картинки.
	 * @returns {HTMLElement}
	 */
	createScreenContainer() {
		// Создаем сам контейнер-обертку и ставим ему класс.
		const galleryWrapperElement = document.createElement('div');
		galleryWrapperElement.classList.add(this.settings.openedImageWrapperClass);

		// Добавляем кнопку назад.
		const backBtn = new Image();
		backBtn.classList.add(this.settings.openedImageBackBtnId);
		backBtn.src = this.settings.openedImageBackBtnSrc;
		galleryWrapperElement.appendChild(backBtn);

		// Добавляем обработчик события при клике, ставим новую открытую картинку и открываем ее.
		backBtn.addEventListener('click', () => {
			this.openedImageEl = this.getPrevImage();
			this.openImage(this.openedImageEl.dataset.full_image_url);
		});

		// Добавляем кнопку вперед.
		const nextBtn = new Image();
		nextBtn.classList.add(this.settings.openedImageNextBtnId);
		nextBtn.src = this.settings.openedImageNextBtnSrc;
		galleryWrapperElement.appendChild(nextBtn);

		// Добавляем обработчик события при клике, ставим новую открытую картинку и открываем ее.
		nextBtn.addEventListener('click', () => {
			this.openedImageEl = this.getNextImage();
			this.openImage(this.openedImageEl.dataset.full_image_url);
		});

		// Создаем контейнер занавеса, ставим ему класс и добавляем в контейнер-обертку.
		const galleryScreenElement = document.createElement('div');
		galleryScreenElement.classList.add(this.settings.openedImageScreenClass);
		galleryWrapperElement.appendChild(galleryScreenElement);

		// Создаем картинку для кнопки закрыть, ставим класс, src и добавляем ее в контейнер-обертку.
		const closeImageElement = new Image();
		closeImageElement.classList.add(this.settings.openedImageCloseBtnClass);
		closeImageElement.src = this.settings.openedImageCloseBtnSrc;
		closeImageElement.addEventListener('click', () => this.close());
		galleryWrapperElement.appendChild(closeImageElement);

		// Создаем картинку, которую хотим открыть, ставим класс и добавляем ее в контейнер-обертку.
		const image = new Image();
		image.classList.add(this.settings.openedImageClass);
		galleryWrapperElement.appendChild(image);

		// Добавляем контейнер-обертку в тег body.
		document.body.appendChild(galleryWrapperElement);

		// Возвращаем добавленный в body элемент, наш контейнер-обертку.
		return galleryWrapperElement;
	},

	/**
	 * Возвращает следующий элемент (картинку) от открытой или первую картинку в контейнере,
	 * если текущая открытая картинка последняя.
	 * @returns {Element} Следующую картинку от текущей открытой.
	 */
	getNextImage() {
		// Получаем элемент справа от текущей открытой картинки.
		const nextSibling = this.openedImageEl.nextElementSibling;
		// Если элемент справа есть, его отдаем, если нет, то берем первый элемент в родительском контейнере.
		return nextSibling ? nextSibling : this.openedImageEl.parentElement.firstElementChild;
	},

	/**
	 * Возвращает предыдущий элемент (картинку) от открытой или последнюю картинку в контейнере,
	 * если текущая открытая картинка первая.
	 * @returns {Element} Предыдущую картинку от текущей открытой.
	 */
	getPrevImage() {
		// Получаем элемент слева от текущей открытой картинки.
		const prevSibling = this.openedImageEl.previousElementSibling;
		// Если элемент слева есть, его отдаем, если нет, то берем последний элемент в родительском контейнере.
		return prevSibling ? prevSibling : this.openedImageEl.parentElement.lastElementChild;
	},

	/**
	 * Закрывает (удаляет) контейнер для открытой картинки.
	 */
	close() {
		document.querySelector(`.${this.settings.openedImageWrapperClass}`).remove();
	}
};

// переделать в объект как в примере выше, а лучше в класс
function buildGoodsList() {
	var $goods = $('#goods');
	var $categoryId = $goods.attr('dataid');
	var $show = $('#show option:selected');
	var $sortBy = $('#sort_by option:selected');
	var $labelRange = $('#label_range').val();
	$.post("?path=categories/getGoods/" + $categoryId, {}, function (goods) {
		var goods = JSON.parse(goods);
		$goods.empty();
		var n = 8;
		switch (+$show.text()) {
			case 9:
				n = 9;
				break;
			case 12:
				n = 12;
				break;
			case 15:
				n = 15;
				break;
			default:
				n = 8;
				break;
		}

		function generateSortPriceFn(prop, reverse) {
			return function (a, b) {
				if (+a[prop] < +b[prop]) return reverse ? 1 : -1;
				if (+a[prop] > +b[prop]) return reverse ? -1 : 1;

				return 0;
			};
		}

		var goodSortByName = goods;
		switch ($sortBy.attr('id')) {
			case 'sortName-down':
				goodSortByName = goods.sort(function (a, b) {
					var nameA = a.name.toLowerCase(),
						nameB = b.name.toLowerCase()
					if (nameA < nameB) return -1;
					if (nameA > nameB) return 1;

					return 0;
				});
				break;

			case 'sortName-up':
				goodSortByName = goods.sort(function (a, b) {
					var nameA = a.name.toLowerCase(),
						nameB = b.name.toLowerCase()
					if (nameA < nameB) return 1;
					if (nameA > nameB) return -1;
					return 0;
				})
				break;

			case 'sortPrice-down':
				goodSortByName = goods.sort(generateSortPriceFn('price', false));
				break;

			case 'sortPrice-up':
				goodSortByName = goods.sort(generateSortPriceFn('price', true));
				break;

			default:
				break;
		}
		goods = goodSortByName;
		if ($labelRange) {
			goods = goods.filter(function (good) {
				return good.price <= +$labelRange;
			});
		}
		goods.slice(0, n).forEach(function (good) {
			var $good = $('<div />', {
				class: 'good button'
			});
			var $catalogItem = $('<a />', {
				href: "?path=categories/good/" + good.id_good,
				class: 'catalog-item'
			});
			var $itemImg = $('<img />', {
				class: 'catalog-img',
				src: good.imgSrc,
				alt: good.name
			});
			// сделать вывод имени бренда или дизайнера перед названием товара
			// расширить запрос в классе good объединение таблиц.
			var $itemName = $('<div />', {
				text: good.name,
				class: 'catalog-name'
			});
			var $itemPrice = $('<div />', {
				text: '$' + good.price,
				class: 'catalog-price'
			});
			var $catalogHover = $('<div />', {
				class: 'catalogHover'
			});
			var $catalogHoverCart = $('<a />', {
				class: 'buy catalogHover-cart button',
				'data-id': good.id_good,
				'data-price': good.price,
				'data-name': good.name,
				'data-imgSrc': good.imgSrc,
				'data-quantity': good.quantity
			});
			var $catalogHoverCartImg = $('<img />', {
				class: 'catalogHover-img',
				src: 'assets/img/icons/button/card_img.png',
				alt: ''
			});
			var $catalogHoverCartText = $('<span />', {
				class: 'catalogHover-text',
				text: 'Add to cart'
			});
			$catalogItem.append($itemImg);
			$catalogItem.append($itemName);
			$catalogItem.append($itemPrice);
			$catalogItem.append($catalogHover);
			$catalogHover.append($catalogHoverCart);
			$catalogHoverCart.append($catalogHoverCartImg);
			$catalogHoverCart.append($catalogHoverCartText);
			$good.append($catalogItem).appendTo('#goods');
		});
	});
}

function buildCart() {
	$.post("?path=order/updateCart", {}, function (items) {
		var $good = $('<ul/>', {
			class: 'basket-good good'
		});
		var total = parseFloat('0');
		var quantity = parseFloat('0');
		if (items) {
			var items = JSON.parse(items);
		}
		var $liveCart = $('#liveCart');

		if (items == 0) {
			$($liveCart[0].firstElementChild.firstElementChild).css('display', 'none');
		}
		for (var good_id in items) {
			quantity += +items[good_id].quantity;
			total += parseFloat(items[good_id].price) * parseFloat(items[good_id].quantity);
			$($liveCart[0].firstElementChild.firstElementChild).css('display', 'flex');
			$liveCart[0].firstElementChild.firstElementChild.firstElementChild.textContent = quantity;
			var $goodItem = $('<li />', {
				id: good_id,
				class: 'good-item',
				'data-id': good_id,
				'data-quantity': items[good_id].quantity
			});
			var $goodImg = $('<img />', {
				class: 'good-img',
				src: '' + items[good_id].imgSrc
			});
			var $goodInfo = $('<div />', {
				class: 'good-info'
			});
			var $goodName = $('<div />', {
				class: 'good-title',
				text: items[good_id].name
			});
			var price = parseFloat(items[good_id].price);
			var $goodPrice = $('<div />', {
				class: 'good-price',
				text: +items[good_id].quantity + ' x ' + price.toFixed(2) + ' $'
			});
			var $goodDeleteIcon = $('<div />', {
				class: 'remove good-deleteIcon deleteIcon'
			});
			var $totalPrice = $('<div />', {
				class: 'basket-liveCartTotalPrice liveCartTotalPrice'
			});
			var $totalText = $('<span />', {
				text: 'TOTAL',
				class: 'liveCartTotalPrice-title'
			});
			var $totalCount = $('<span />', {
				text: '$' + total.toFixed(2),
				class: 'liveCartTotalPrice-count'
			});
			if ($('#shCartSubTotal') && $('#shCartGrandTotal')) {
				$('#shCartSubTotal').children().detach();
				$('#shCartGrandTotal').children().detach();
				var $subTotalCount = $('<span />', {
					text: '$' + total.toFixed(2),
				});
				var $grandTotalCount = $('<span />', {
					text: '$' + total.toFixed(2),
				});
				// $subTotalCount.empty();
				// $grandTotalCount.empty();
				$('#shCartSubTotal').append($subTotalCount);
				$('#shCartGrandTotal').append($grandTotalCount);
			}
			// console.log($('#shCartSubTotal')[0].children);
			$goodItem.append($goodImg);
			$goodItem.append($goodInfo);
			$goodInfo.append($goodName);
			$goodInfo.append($goodPrice);
			$goodItem.append($goodDeleteIcon);
			$good.append($goodItem);
		}
		var $cart = $('#cart');
		$cart.empty();
		$cart.append($good);
		if ($totalPrice) {
			$cart.append($totalPrice);
			$totalPrice.append($totalText);
			$totalPrice.append($totalCount);

		}

		// переход по клику на товар в виджете корзина
		$('li.good-item').on('click', function (e) {
			let id = $(this).attr('data-id');
			if (e.target.className !== 'remove good-deleteIcon deleteIcon') {
				window.location.href = "?path=categories/good/" + id;
			}
		});

		$('li.shoppingCart_item').on('click', function (e) {
			let id = $(this).attr('data-id');
			if (e.target.className == 'good-img' ||
				e.target.className == 'sh_item_title-text') {
				window.location.href = "?path=categories/good/" + id;
			}
		});

	});
}

const authPage = {
	event: 'click',
	controller: '?path=auth',
	props: {
		$eTarget: null,
		eTargetId: null,
		method: null,
		itemBtn: null,
		itemId: null,
		clickedItemId: null,
		view: null
	},

	init() {
		if (document.location.search == this.controller) {
			this.eventListener();
		}
		return;
	},

	eventListener() {
		$(document).on(this.event, function (e) {
			authPage.setProps(e.target);
			authPage.checkedActions();
		});
	},

	setProps(eTarget) {
		this.props.$eTarget = $(eTarget);
		this.props.eTargetId = eTarget.id;
		this.props.itemBtn = this.props.$eTarget.attr('id');
	},

	checkedActions() {
		if (this.props.$eTarget.prop('tagName') == 'BUTTON' &&
			this.props.$eTarget.attr('name') != 'login_user' &&
			this.props.$eTarget.attr('name') != 'reg_user') {
			this.props.clickedItemId = '#' + this.props.$eTarget.closest(".formBlock").parent().attr('id');
			this.props.method = this.props.itemBtn.replace('-btn', '');
			this.props.itemId = '#' + this.props.method + '-popupElement';
			this.props.view = this.controller + '/' + this.props.method + '&popup';
			this.openPopupForm();
			return;
		}
		if (this.props.eTargetId == 'closeBtn') {
			this.closePopupFormBtn();
			return;
		}
		if (!this.props.$eTarget.closest(".formBlock").length &&
			this.props.$eTarget.prop('tagName') != 'BUTTON' &&
			this.props.clickedItemId) {
			this.closePopupForm();
			return;
		}
	},

	openPopupForm() {
		$.post(this.props.view, function (data) {
			// data = JSON.parse(data);
			$('.auth_section').after(data);
		});
		// history.pushState({
		// 	page: document.URL
		// }, null, document.URL + '/' + this.props.method);
		// console.log(history.state)
	},

	closePopupFormBtn() {
		$(this.props.itemId).detach();
		this.resetProps();
		// history.back();
	},

	closePopupForm() {
		$(this.props.itemId).detach();
		this.resetProps();
		// history.back();
		return;
	},

	resetProps() {
		this.props.$eTarget = null;
		this.props.eTargetId = null;
		this.props.itemBtn = null;
		this.props.clickedItemId = null;
		this.props.method = null;
		this.props.itemId = null;
		this.props.view = null;
	}
};

class CheckOutPage_SignUp {
	constructor() {
		this.chengeRadio();
		this.openPopUp();
		this.closePopUp();
	}

	chengeRadio() {
		$('input[type="radio"]').on('change', function (e) {
			$('#' + e.target.id).attr('checked', true);
			if (e.target.id != 'checkOutSignUp') $('#checkOutSignUp').attr('checked', false);
			if (e.target.id != 'checkOutAsGuest') $('#checkOutAsGuest').attr('checked', false);
		});
	}

	openPopUp() {
		$('#checkOutSignUp_Btn').on('click', function (e) {
			if ($('#checkOutSignUp').attr('checked')) {
				// console.log(e);
				e.preventDefault();
				$.get('?path=auth/sign_up&popup', function (data) {
					$('.main-checkout_section').after(data);
				});
			}
		});
	}

	closePopUp() {
		$(document).on('click', function (e) {
			if (e.target.id == 'closeBtn') {
				$('#sign_up-popupElement').detach();
				// history.back();
				return;
			}
			if (!$(e.target).closest(".formBlock").length) {
				$('#sign_up-popupElement').detach();
			}
		});
	}
};

window.onload = function () {
	// Инициализируем нашу галерею при загрузке страницы.
	if (document.querySelector('.gallery-photos')) {
		images.init({
			previewSelector: '.gallery-photos'
		});
	}
	if (document.querySelector('.gallery-photos')) {
		gallery.init({
			previewSelector: '.gallery-photos'
		});
	}

	(function ($) {
		$(function () {
			buildGoodsList();
			buildCart();
			/** 
			 * Header корзина
			 */
			// кнопка корзаны, открыть-закрыть
			$('#liveCartEvent').on('click', function (e) {
				$('.myAcc-dropdown').hide();
				if (e.target != this || $('#cart').children().first().children().length == 0) {
					return;
				}
				$('.basket-dropdown').toggle();
				e.stopPropagation();
			});
			$(document).on('click', function (e) {
				if (!$(e.target).closest(".header-basket").length) {
					$('.basket-dropdown').hide();
				}
				e.stopPropagation();
			});

			// удалить из корзины товар
			$('#cart').on('click', '.remove', function (event) {
				var goodId = $(this).parent().attr('data-id');
				$.ajax({
					type: "POST",
					url: "?path=order/removeGoodFromCart",
					data: {
						'id_good': goodId
					},
					success: function success() {
						if (($('#cart').children().first().children().length - 1) == 0) {
							$('.basket-dropdown').hide();
						}
						$('li[data-id = ' + goodId + ']').next().detach();
						$('li[data-id = ' + goodId + ']').detach();
						$('#buy').load(document.URL + ' .buy');
						buildCart();
						buildGoodsList();
					}
				});
			});
			// удалить со страницы Cart
			$('#busket').on('click', '.remove', function (event) {
				var goodId = $(this).parent().attr('data-id');
				$.ajax({
					type: "POST",
					url: "?path=order/removeGoodFromCart",
					data: {
						'id_good': goodId
					},
					success: function success() {
						$('li[data-id = ' + goodId + ']').next().detach();
						$('li[data-id = ' + goodId + ']').detach();
						buildCart();
					}
				});
			});

			/** 
			 * Cart index
			 */
			// Обработчик колличества товара в cart index
			$('#cart_goodQuantity').on('change', function (event) {
				// console.log($(this).attr('data-id'));
				let $goodId = $(this).attr('data-id');
				let $count = $(this).val();
				$.ajax({
					type: "POST",
					url: "?path=order/updateGoodCount",
					data: {
						'id_good': $goodId,
						'count': $count
					},
					success: function success(result) {
						buildCart();
						buildGoodsList();
					}
				});
			});

			/** 
			 * Header My Account
			 */
			// кнопка аккаунт, открыть-закрыть
			$('#myAcc').on('click', function (e) {
				$('.basket-dropdown').hide();
				$('.myAcc-dropdown').toggle();
				e.stopPropagation();
			});
			$(document).on('click', function (e) {
				if (!$(e.target).closest(".header-myAcc").length) {
					$('.myAcc-dropdown').hide();
				}
				e.stopPropagation();
			});

			/** 
			 * Страница авторизации
			 */

			// замена url. Убрать ?path=
			/* 
				window.history.replaceState(
					{}, 
					"", 
					document.location.origin + document.
					location.search.replace('?path=', '/')); 
			*/
			/* 
				function updateURL() {
					if (history.pushState) {
						var baseUrl = document.URL;
						var newUrl = baseUrl + '/sign_in';
						history.pushState(null, null, newUrl);
					} else {
						console.warn('History API не поддерживается');
					}
				}
				console.log(document.URL + '/sign_in');
				console.log(document.location.origin + document.location.search.replace('?path=', '/'));
				console.log(document.location.origin + document.location.search.replace('?path=', '/') + '/sign_in'); 
			*/
			new CheckOutPage_SignUp();

			authPage.init();

			/** 
			 * Navbar
			 */
			$('.navbar-dropdown').hover(function () {
				$(this).next().show();
				$(this).next().hover(function () {
					$(this).show();
				}, function () {
					$(this).hide();
				});
			}, function () {
				$(this).next().hide();
				this.stopPropagation;
			});

			/** 
			 * Categories
			 */
			// сортировать товары
			$('#show').on('click', function () {
				buildGoodsList();
			});
			$('#sort_by').on('click', function () {
				buildGoodsList();
			});
			$('#label_range').on('change', function () {
				var value = $(this).val();
				buildGoodsList();
			});

			/** 
			 * Categories
			 */
			// добавить товар
			$('#goods').on('click', '.buy', function (event) {
				if (+$(this).attr('data-quantity') < 1) {
					alert('Недостаточно товара');
					return;
				}
				var good = {
					id_good: $(this).attr('data-id'),
					name: $(this).attr('data-name'),
					imgSrc: $(this).attr('data-imgSrc'),
					price: $(this).attr('data-price')
				};

				var cartGood = $('#cart li[data-id="' + $(this).attr('data-id') + '"]');
				if (cartGood.length) {
					good.quantity = 1;
					$.post("?path=cart/addToCard", {
						id_good: good.id_good,
						quantity: good.quantity
					}, function () {
						buildCart();
						buildGoodsList();
					});
				} else {
					good.quantity = 1;
					$.post("?path=cart/addToCard", {
						id_good: good.id_good,
						quantity: good.quantity
					}, function () {
						buildCart();
						buildGoodsList();
					});
				}
				event.preventDefault();
			});

			/** 
			 * Categories Good
			 */
			// добавление из карточки товара
			$('#good').on('click', '.buy', function (event) {
				if (+$(this).attr('data-quantity') < 1) {
					alert('Недостаточно товара');
					return;
				}
				var good = {
					id_good: $(this).attr('data-id'),
					name: $(this).attr('data-name'),
					imgSrc: $(this).attr('data-imgSrc'),
					price: $(this).attr('data-price')
				};

				var cartGood = $('#cart li[data-id="' + $(this).attr('data-id') + '"]');
				let $goodCount = $('#good_goodQuantity').val();
				if (cartGood.length) {
					// console.log($goodCount);
					// console.log(+cartGood.eq(0).attr('data-quantity'));
					good.quantity = $goodCount;
					// console.log(good.quantity);
					$.ajax({
						type: "POST",
						url: "?path=cart/addToCard",
						data: {
							id_good: good.id_good,
							quantity: good.quantity
						},
						success: function success() {
							buildCart();
							buildGoodsList();
							$('#buy').load(document.URL + ' .buy');
						}
					});
				} else {
					good.quantity = $goodCount;
					$.post("?path=cart/addToCard", {
						id_good: good.id_good,
						quantity: good.quantity
					}, function () {
						buildCart();
						buildGoodsList();
						$('#buy').load(document.URL + ' .buy');
					});
				}
				event.preventDefault();
			});

			/** 
			 * Admin
			 */
			// Админка, показать информацию заказа
			$('div.order__absolute').hide();
			$('div.order-id').hover(function () {
				// console.log($(this).children());
				$(this).children().show();
				var $id_order = $(this).parent()[0].id;
				$id_order = $id_order.match(/\d+/)[0]
				// console.log($id_order);
				$.ajax({
					type: "POST",
					url: "/?path=admin/getOrderInfo",
					data: {
						'id_order': $id_order
					},
					success: function success(info) {
						var info = JSON.parse(info);
						var idValues = '#orderInfoValues-' + $id_order;
						// console.log(info.first_name);
						$(idValues).children().detach();
						var $orderInfoName = $('<div />', {
							class: 'orderInfo-info',
							text: info.first_name + ' ' + info.last_name
						});
						var $orderInfoAddress = $('<div />', {
							class: 'orderInfo-info',
							text: info.address + ' (' + info.city + ' - ' + info.state + ')'
						});
						var $orderInfoPhone = $('<div />', {
							class: 'orderInfo-info',
							text: info.phone_number
						});
						$(idValues).append($orderInfoName);
						$(idValues).append($orderInfoAddress);
						$(idValues).append($orderInfoPhone);
					}
				});
			}, function () {
				$(this).children().hide();
			});

			// Админка, показать товары заказа
			class accountViewBasket {
				constructor() {
					this.id = {
						id_order: null,
						parentId: null,
						orderBasketId: null,
						basketId: null,
					};
					this.status = {
						dispEl: {
							id: null,
							displayed: false,
						},
						displayedElements: new Array(),
						loaded: false,
					};
					this.eventListener();
				}

				eventListener() {
					$('.order-goods').on('click', function (e) {
						this.id.parentId = '#' + $(e.target).parent()[0].id;
						this.id.id_order = this.id.parentId.match(/\d+/)[0];
						this.id.orderBasketId = '#orderBasket-' + this.id.id_order;
						this.id.basketId = '#basket-' + this.id.id_order;
						this.checkOpenedStatus();
					}.bind(this));
				}

				checkOpenedStatus() {
					if (this.status.dispEl.id == null &&
						this.status.dispEl.displayed == false) {

						this.checkLoadStatus();
						this.showHide('show&load-first');
						// console.log('первая проверка show&load-first');
						return;

					} else if (this.status.dispEl.id == this.id.orderBasketId &&
						this.status.dispEl.displayed == true) {

						this.showHide('hide');
						// console.log('вторая проверка hide');
						return;

					} else if (this.status.dispEl.id == this.id.orderBasketId &&
						this.status.dispEl.displayed == false) {

						this.showHide('show_again');
						// console.log('третья проверка show_again');
						return;

					} else if (this.status.dispEl.id != this.id.orderBasketId &&
						this.status.dispEl.displayed == true) {

						this.checkLoadStatus();
						this.showHide('hide&show');
						// console.log('четвертая проверка hide&show');
						return;

					} else if (this.status.dispEl.id != this.id.orderBasketId &&
						this.status.dispEl.displayed == false) {

						this.checkLoadStatus();
						this.showHide('show_new');
						// console.log('пятая проверка show_new');
						return;

					}
					// console.log('отображен элемент' + this.status.dispEl);
				}

				checkLoadStatus() {

					if (!this.status.displayedElements.includes(this.id.orderBasketId)) {
						this.status.displayedElements.push(this.id.orderBasketId);
						this.getOrderGoods();
					}
					// console.log(this.status.displayedElements + ' такой есть в массиве, но не был открытым не грузим');
				}

				showHide(action) {
					switch (action) {
						case 'show&load-first':
							$(this.id.orderBasketId).show();
							this.status.dispEl.id = this.id.orderBasketId;
							this.status.dispEl.displayed = true;
							// console.log('отобразил первый ' + 'Массив: ' + this.status.displayedElements);
							break;
						case 'hide':
							$(this.id.orderBasketId).hide();
							this.status.dispEl.displayed = false;
							// console.log('скрыл ' + this.status.displayedElements);
							break;
						case 'show_again':
							$(this.id.orderBasketId).show();
							this.status.dispEl.displayed = true;
							// console.log('открыл повторно ' + this.status.dispEl.id);
							break;
						case 'hide&show':
							$(this.status.dispEl.id).hide();
							$(this.id.orderBasketId).show();
							this.status.dispEl.id = this.id.orderBasketId;
							this.status.dispEl.displayed = true;
							break;
						case 'show_new':
							$(this.id.orderBasketId).show();
							this.status.dispEl.id = this.id.orderBasketId;
							this.status.dispEl.displayed = true;
							break;
							// default:
							// 	$(this.id.$orderBasketId).hide();
							// 	this.status.displayed = false;
							// 	break;
					}
				}

				getOrderGoods() {
					$.ajax({
						type: "POST",
						url: "/?path=admin/getOrderGoods",
						data: {
							'id_order': this.id.id_order
						},
						success: function success(data) {
							data = JSON.parse(data);
							// $(idValues).detach('.adminOrderBasket-item');
							for (let item in data) {
								const basketId = '#basket-' + data[item].id_order;
								const $basketId = $(basketId);
								var $goodNumber = $('<div />', {
									class: 'orderBasket-info',
									text: '№ ' + (+item + 1)
								});
								var $goodImg = $('<img />', {
									class: 'orderBasket-info orderBasket-img',
									src: data[item].imgSrc
								});
								var $goodName = $('<div />', {
									class: 'orderBasket-info',
									text: data[item].name
								});
								var $goodAttr = $('<div />', {
									class: 'orderBasket-info',
									text: "Size: 'XL' \n Color: 'Red'"
								});
								var $goodIsInOrder = $('<div />', {
									class: 'orderBasket-info',
									text: data[item].is_in_order
								});
								var $goodPrice = $('<div />', {
									class: 'orderBasket-info',
									text: '$' + data[item].price
								});
								var $goodTotalPrice = $('<div />', {
									class: 'orderBasket-info',
									text: '$' + data[item].total
								});
								$($basketId.append($goodNumber));
								$($basketId.append($goodImg));
								$($basketId.append($goodName));
								$($basketId.append($goodAttr));
								$($basketId.append($goodIsInOrder));
								$($basketId.append($goodPrice));
								$($basketId.append($goodTotalPrice));
							}
						}
					});
				}
			}
			new accountViewBasket();
			// Админка, показать товары заказа

			// Админка управление заказами
			$('.btn_process').on('click', function () {
				let data = $(this).data('id');
				$.post(
					'/?path=admin/updateOrderStatus', {
						id_order: data
					},
					function (response, status) {
						if (status == "success") {
							var response = JSON.parse(response);
							if (response.status == 'OK') {
								$('#order-' + data + ' td.field-status').text(response.order_status);
							} else {
								alert(response.message);
							}
						} else {
							alert('ERROR');
						}
					}
				);
			});
			$('.btn_remove').on('click', function () {
				let data = $(this).data('id');
				$.post(
					'/?path=admin/removeOrder', {
						id_order: data
					},
					function (response, status) {
						if (status == "success") {
							var response = JSON.parse(response);
							if (response.status == 'OK') {
								$('#order-' + data).remove();
							} else {
								alert(response.message);
							}
						} else {
							alert('ERROR');
						}
					}
				);
			});


			/** 
			 * Reviews
			 */
			$('#editReviewBtn').on('click', function(event){
				
				console.log($('#content').html());
				// console.log($(this).html());
			});
			$('#editReviewBtn').each(function(){
				// выведем содержимое текущего элемента в консоль
				console.log($(this).html());
			});
		});
	})(jQuery);

};





// $(document).ready(function () {
// 	$("#checkout_as_guest").click(function () {
// 		$("#register").attr('checked', false);
// 	});
// });

// var _createClass = function () {
// 	function defineProperties(target, props) {
// 		for (var i = 0; i < props.length; i++) {
// 			var descriptor = props[i];
// 			descriptor.enumerable = descriptor.enumerable || false;
// 			descriptor.configurable = true;
// 			if ("value" in descriptor) descriptor.writable = true;
// 			Object.defineProperty(target, descriptor.key, descriptor);
// 		}
// 	}
// 	return function (Constructor, protoProps, staticProps) {
// 		if (protoProps) defineProperties(Constructor.prototype, protoProps);
// 		if (staticProps) defineProperties(Constructor, staticProps);
// 		return Constructor;
// 	};
// }();

// function _possibleConstructorReturn(self, call) {
// 	if (!self) {
// 		throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
// 	}
// 	return call && (typeof call === "object" || typeof call === "function") ? call : self;
// }

// function _inherits(subClass, superClass) {
// 	if (typeof superClass !== "function" && superClass !== null) {
// 		throw new TypeError("Super expression must either be null or a function, not " + typeof superClass);
// 	}
// 	subClass.prototype = Object.create(superClass && superClass.prototype, {
// 		constructor: {
// 			value: subClass,
// 			enumerable: false,
// 			writable: true,
// 			configurable: true
// 		}
// 	});
// 	if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass;
// }

// function _classCallCheck(instance, Constructor) {
// 	if (!(instance instanceof Constructor)) {
// 		throw new TypeError("Cannot call a class as a function");
// 	}
// }

// var EventListener = function () {
// 	function EventListener() {
// 		_classCallCheck(this, EventListener);

// 		this.listenEvents = "click";
// 		this.eventBlock = ".site";
// 		this.eventElement = ".siteEvent";
// 		this.eventHiddenElement = ".siteEvent-element";
// 		this.status = {
// 			displayed: false,
// 			prevDisplayedElement: "",
// 			displayedElement: ""
// 		};
// 	}

// 	_createClass(EventListener, [{
// 		key: "listener",
// 		value: function listener() {
// 			var _this = this;

// 			$(this.eventBlock).on(this.listenEvents, function (event) {
// 				return _this.eventElementChecker(event.target);
// 			});
// 		}
// 	}, {
// 		key: "eventElementChecker",
// 		value: function eventElementChecker(e) {
// 			if (this.eventElement === "#" + e.id || this.eventElement === "." + e.className || this.eventElement === "" + e.tagName) return this.showHide($(this.eventHiddenElement));
// 			else return this.hideElement($(this.eventHiddenElement));
// 		}
// 	}, {
// 		key: "showHide",
// 		value: function showHide(arg) {
// 			if (this.status.displayed === false) {
// 				return this.showElement(arg);
// 			} else return this.hideElement(arg);
// 		}
// 	}, {
// 		key: "showElement",
// 		value: function showElement(element) {
// 			this.status.displayed = true;
// 			this.status.displayedElement = element;
// 			return $(element).show();
// 		}
// 	}, {
// 		key: "hideElement",
// 		value: function hideElement(element) {
// 			this.status.displayed = false;
// 			this.status.prevDisplayedElement = this.status.displayedElement;
// 			this.status.displayedElement = "";
// 			return $(element).hide();
// 		}
// 	}]);

// 	return EventListener;
// }();

// var DropdownBtnListener = function (_EventListener) {
// 	_inherits(DropdownBtnListener, _EventListener);

// 	function DropdownBtnListener(eventElement, eventHiddenElement) {
// 		_classCallCheck(this, DropdownBtnListener);

// 		var _this2 = _possibleConstructorReturn(this, (DropdownBtnListener.__proto__ || Object.getPrototypeOf(DropdownBtnListener)).call(this));

// 		_this2.eventElement = eventElement;
// 		_this2.eventHiddenElement = eventHiddenElement;
// 		_this2.status = {
// 			displayed: false,
// 			prevDisplayedElement: "",
// 			displayedElement: ""
// 		};
// 		return _this2;
// 	}

// 	_createClass(DropdownBtnListener, [{
// 		key: "eventElementChecker",
// 		value: function eventElementChecker(e) {
// 			if (this.eventElement === "#" + e.id ||
// 				this.eventElement === "." + e.className ||
// 				this.eventElement === "" + e.tagName) {
// 				if ($(e)[0].firstElementChild === null) {
// 					return this.showHide($(e)[0].nextElementSibling);
// 				}
// 				return this.showHide($(e)[0].firstElementChild);
// 			}
// 			// подумать как реализовать, чтобы корзина не закрывалась при добавлении товара
// 			// || e.className == $('.buy')[0].className 
// 			// || e.parentElement.className == $('.buy')[0].className 
// 			else if (this.eventHiddenElement === "#" + e.id ||
// 				this.eventHiddenElement === "." + e.className ||
// 				this.eventHiddenElement === "." + e.offsetParent.className) {
// 				return true;
// 			} else return this.hideElement($(this.eventHiddenElement));
// 		}
// 	}]);

// 	return DropdownBtnListener;
// }(EventListener);

// /**
//  * EventListener
//  * @type {DropdownBtnListener}
//  */


// var eventMyAcc = new DropdownBtnListener("#myAcc", ".myAcc-dropdown");
// eventMyAcc.listener();
// var eventLiveCart = new DropdownBtnListener("#liveCartEvent", ".basket-dropdown");
// eventLiveCart.listener();

// class EventListener {
// 	constructor() {
// 		this.crnTarget = ".site";
// 		this.id = ["#liveCart", "#myAcc"];
// 		this.events = "click";
// 		this.toggleItem = "-dropdown";
// 		this.status = {
// 			showHideEmpty: true,
// 			prevShowedElement: "",
// 			nowShowingElement: ""
// 		};
// 	}
//
// 	listener() {
// 		$(this.crnTarget).on(this.events, event => this.checkElement(event.target));
// 	}
//
// 	checkElement(e) {
// 		let [id1, id2] = this.id;
// 		if (id2 === `#${e.id}`) {
// 			return this.showHide(`.${e.className}${this.toggleItem}`);
// 		}
// 		else
// 		if (id1 === `#${e.offsetParent.id}`) {
// 			return this.showHide(`.${e.offsetParent.className}${this.toggleItem}`);
// 		}
// 		else
// 		if (this.status.nowShowingElement) {
// 			return this.showHide(this.status.nowShowingElement);
// 		}
// 	}
//
// 	showHide(element) {
// 		if (this.status.showHideEmpty) {
// 			return this.showElement(element);
// 		}
// 		else
// 		if (element !== this.status.nowShowingElement) {
// 			this.hideElement(this.status.nowShowingElement);
// 			return this.showElement(element);
// 		}
// 		else {
// 			return this.hideElement(element);
// 		}
// 	}
//
// 	showElement(element) {
// 		$(element).show();
// 		this.status.showHideEmpty = false;
// 		this.status.nowShowingElement = element;
// 	}
//
// 	hideElement(element) {
// 		$(element).hide();
// 		this.status.showHideEmpty = true;
// 		this.status.prevShowedElement = element;
// 		this.status.nowShowingElement = "";
// 	}
// }
//
// // class MenuEvent extends EventListener {
// // 		constructor() {
// 			super();
// 			this.$menuLink = $(".menu-link");
// 			this.$supermenu = $("#supermenu");
// 			this.supermenuStyleOn = {
// 				display: "grid",
// 				opacity: "1"
// 			};
// 			this.supermenuStyleOff = {
// 				opacity: "0",
// 				display: "none"
// 			};
// 		}
//
// 		listen() {
// 			// поместить из menuLinkHover и submenuHover, а в ней оставить только стили
// 		}
//
// 		menuLinkHover() {
// 			this.$menuLink.on("mouseenter mouseleave", event => {
// 					if (event.target.innerText !== "MAN") return;
// 					if (event.type !== "mouseleave") {
// 						this.$supermenu.css(this.supermenuStyleOn);
// 						return this.submenuHover();
// 					}
//
// 					this.$supermenu.css(this.supermenuStyleOff);
// 				});
// 		}
//
// 		submenuHover() {
// 			this.$supermenu.on("mouseenter mouseleave", event => {
// 					if (event.type !== "mouseleave") {
// 						this.$supermenu.css(this.supermenuStyleOn);
// 						return;
// 					}
//
// 					this.$supermenu.css(this.supermenuStyleOff);
// 				});
// 		}
// 	}