window.onload = function () {
    function a(a, b) {
        var c = /^(?:file):/, d = new XMLHttpRequest, e = 0;
        d.onreadystatechange = function () {
            4 == d.readyState && (e = d.status), c.test(location.href) && d.responseText && (e = 200), 4 == d.readyState && 200 == e && (a.outerHTML = d.responseText)
        };
        try {
            d.open("GET", b, !0), d.send()
        } catch (f) {
        }
    }

    var b, c = document.getElementsByTagName("*");
    for (b in c) c[b].hasAttribute && c[b].hasAttribute("data-include") && a(c[b], c[b].getAttribute("data-include"))
};

function bodyClass($class) {
    return $('body').hasClass($class);
}

function $_GET(url) {
    var query = url.split('?')[1];
    var result = {};
    query.split("&").forEach(function (part) {
        var item = part.split("=");
        result[item[0]] = decodeURIComponent(item[1]);
    });
    return result;
}

function jsonToPost(json) {
    return Object.keys(json).map(function (k) {
        return encodeURIComponent(k) + '=' + encodeURIComponent(json[k])
    }).join('&')
}

class Qty {
    enable() {
        $(':input[name=update_cart]').removeAttr('disabled');
    }

    trigger() {
        $(':input[name=update_cart]').trigger("click");
    }

    constructor() {
        setTimeout(() => this.enable());
        $(document.body).on('updated_cart_totals', () => this.enable());
        $('body').on('change', '.qty', () => {
            this.enable();
            this.trigger();
        });
        this.watch();
    }

    watch() {
        $('body').on('click', '.qty-btn', (e) => {
            e.preventDefault();

            let $this = $(e.currentTarget);
            let $input = $this.parent().find('input');

            let current = Math.abs(parseInt($input.val()));

            if ($this.hasClass('qty-plus')) {
                $input.val(++current).trigger("change");
            } else if (current > 0) {
                $input.val(--current).trigger("change");
            }
        });
    }
}

class Woo {
    constructor() {
        this.url = AdminAjax;

        new Qty();

        $('a.add-to-cart').click((e) => {
            e.preventDefault();
            let $btn = $(e.currentTarget);
            let id = $btn.attr('data-id');
            this.addToCart(id, 1, $btn);
        });
        $('button.add-to-cart').click((e) => {
            e.preventDefault();
            let $btn = $(e.currentTarget),
                id = $btn.val(),
                count = $('.qty').val();
            this.addToCart(id, count, $btn);
        });

        if ($('.filters').length !== 0) this.filters();

        if (bodyClass('home')) {
            this.categories();
        }
        if (bodyClass('single-product')) {
            this.gallery();
        }

        $('.sort-line select').change(function () {
            $(this).closest('form').submit();
        });
    }

    addToCart(id, qty = 1, $btn) {
        let data = {
            action: 'add_to_cart',
            id,
            qty
        };
        $btn.addClass('loading');
        $.post(this.url, data, (res) => {
            res = JSON.parse(res);
            if (res.status === 'ok') {
                this.updateCart(res.cart);
                this.updateModal(res.modal);
                $('.notices-area').fadeOut(function () {
                    $(this).empty().append(res.notice).fadeIn();
                });
            }
        }).done(function () {
            $btn.removeClass('loading');
            $btn.addClass('added');
        });
    }

    createOrder() {
        let data = {
            action: 'create_order',
            name: $('#name').val(),
            tel: $('#tel').val(),
            billing: $('#billing').val(),
            payment: $('#payment').val(),
            comment: $('#comment').val(),
        };
        $.post(this.url, data, (res) => {
            res = JSON.parse(res);
            if (res.status === 'ok') {
                this.updateCart(res.cart);
            }
        });
    }

    updateCart(cart) {
        $('.mini-cart').replaceWith(cart);
    }

    updateModal(modal) {
        $('#cart-added').replaceWith(modal);
        $('#cart-added').modal('show');
    }

    filters() {
        if ($().select2) {
            $('select').select2({
                minimumResultsForSearch: -1
            });
        }
        this.rangeSlider();
        $('.show-more').click(function (e) {
            e.preventDefault();
            $(this).siblings('.hidden').slideToggle();
            if ($(this).hasClass('active')) {
                $(this).text('Скрыть');
            } else {
                $(this).text('Показать все параметры');
            }
            $(this).toggleClass('active');
        });
        $('.filter-block input[type=checkbox]').click(function () {
            let queryString = '',
                inputs = $(this).parent().parent().find('input[type=checkbox]:checked');

            inputs.each(function (index) {
                queryString += $(this).val();
                if (index !== inputs.length - 1) {
                    queryString += ',';
                }
            });
            $(this).parent().parent().find('.result').val(queryString);
        });
    }

    rangeSlider() {
        let $from = $('#price-from'),
            $to = $('#price-to');

        if ($().slider) {
            $("#slider-range").slider({
                range: true,
                min: parseInt($from.attr('data-min')),
                max: parseInt($to.attr('data-max')),
                values: [parseInt($from.val()), parseInt($to.val())],
                slide: function (event, ui) {
                    $from.val($("#slider-range").slider("values", 0));
                    $to.val($("#slider-range").slider("values", 1));
                }
            });
        }
    }

    gallery() {
        $('.gallery .thumbnail').click(function () {
            let src = $(this).attr('data-src'),
                $main = $('.gallery .main-photo');
            if ($main.attr('data-src') !== src) {
                $('.thumbnail.active').removeClass('active');
                $(this).addClass('active');
                $main.fadeOut(function () {
                    $(this).attr('href', src);
                    $(this).css('background-image', `url('${src}')`);
                    $(this).fadeIn();
                });
            }
        });
        if (window.innerWidth > 576) {
            $(".thumbnails").slick({
                infinite: false,
                vertical: true,
                slidesToShow: 3,
                prevArrow: "<button type=\"button\" class=\"slick-prev\"><i class='fal fa-chevron-up'></i></button>",
                nextArrow: "<button type=\"button\" class=\"slick-prev\"><i class='fal fa-chevron-down'></i></button>",
            });
        } else {
            $(".thumbnails").slick({
                infinite: false,
                slidesToShow: 2,
                // centerMode:true,
                prevArrow: "<button type=\"button\" class=\"slick-prev\"><i class='fal fa-chevron-left'></i></button>",
                nextArrow: "<button type=\"button\" class=\"slick-prev\"><i class='fal fa-chevron-right'></i></button>",
            });
        }
    }

    categories() {
        $('.categories-list a').click(function (e) {
            e.preventDefault();
            let id = $(this).attr('data-id');
            if (!$(this).hasClass('active')) {
                $('.categories-list .active').removeClass('active');
                $(this).addClass('active');
                $('.products-tab.active').fadeOut(function () {
                    $(this).removeClass('active');
                    $(`.products-tab[data-id=${id}]`).fadeIn().addClass('active');
                });
            }
        });
    }
}

$(() => {
    new Woo();
    let carouselNavText = ['<i class="fal fa-chevron-left"></i>', '<i class="fal fa-chevron-right"></i>'];
    $('.reviews-carousel').owlCarousel({
        items: 1,
        navText: carouselNavText,
        nav: false,
        responsive: {
            991: {
                items: 2,
                nav: true,
            },
            576: {
                nav: true,
            }
        }
    });
    $('.top-carousel').owlCarousel({
        items: 1,
        navText: carouselNavText,
        nav: true
    });
    $('input[type=tel]').inputmask("mask", {"mask": "+7 (999) 999-99-99", "clearIncomplete": true});
    $('.contact-form .submit, .contacts-form .submit').click(function (e) {
        if (!$(this).closest('form')[0].reportValidity())
            e.preventDefault();
    });
});