@extends('frontend.include.app')
@section('content')
    @php
    use RealRashid\SweetAlert\Facades\Alert;
    @endphp
    <div class="page-content page-home">
        <section class="store-carousel">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 mt-5" data-aos="zoom-in">
                        <div id="storeCarousel" class="carousel slide" data-ride="carousel">
                            <ol class="carousel-indicators">
                                <li data-target="#storeCarousel" data-slide-to="0" class="active"></li>
                                <li data-target="#storeCarousel" data-slide-to="1"></li>
                                <li data-target="#storeCarousel" data-slide-to="2"></li>
                            </ol>
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img src="{{ asset('frontend/images/food/salads.jpg') }}" height="600px"
                                        style="border-radius: 20px" class="d-block w-100 " alt="Carousel Image" />
                                    <div class="carousel-caption d-none d-md-block">
                                        <h5>Appetizer</h5>
                                    </div>
                                </div>
                                <div class="carousel-item">
                                    <img src="{{ asset('frontend/images/food/minuman.jpg') }}" class="d-block w-100 "
                                        style="border-radius: 20px" height="600px" alt="Carousel Image" />
                                    <div class="carousel-caption d-none d-md-block">
                                        <h5>Minuman</h5>
                                    </div>
                                </div>
                                <div class="carousel-item">
                                    <img src="{{ asset('frontend/images/food/nasi-goreng.jpg') }}" class="d-block w-100 "
                                        style="border-radius: 20px" height="600px" alt="Carousel Image" />
                                    <div class="carousel-caption d-none d-md-block">
                                        <h5>Makanan Utama</h5>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="menus my-5">
            <div class="container">
                <div class="row">
                    @foreach ($suggestproduct as $item)
                        <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                            <div class="menu-item" style="position: relative">
                                <a href="{{ route('detail', $item->id) }}">
                                    <img src="{{ Storage::url($item->ThumbnailPhoto) }}" style="object-fit: cover"
                                        alt="Makeup Categories" class="w-100 shadow" height="217px" />
                                    <span class="btn btn-primary text-mute price-menu" style=""> Rp
                                        {{ $item->Price }}</span>
                                </a>
                            </div>
                            <p class="title-menu my-2"> {{ $item->ProductName }} </p>
                            <div class="row mx-auto">
                                @auth
                                    <form action="{{ route('detail-add', $item->id) }}" method="POST"
                                        enctype="multipart/form-data" class="mr-2">
                                        @csrf
                                        <button type="submit" class="btn btn-success px-2 text-white btn-block mb-3"
                                            style="color: #fb6387">
                                            Buy Now
                                        </button>
                                    </form>
                                    <form action="{{ route('detail-add', $item->id) }}" method="post">
                                        @csrf
                                        <input type="hidden" name="addtocard" class="addtocard" value="true">
                                        <input type="hidden" name="id" value="{{ $item->id }}" class="product-id">
                                        <button type="submit" class="btn add-to-card px-4 btn-block mb-3">
                                            Add to cart
                                        </button>
                                    </form>
                                @endauth
                                @guest
                                    <form action="{{ route('detail-add', $item->id) }}" method="POST"
                                        enctype="multipart/form-data" class="mr-2">
                                        @csrf
                                        <button type="submit" class="btn btn-success px-2 text-white btn-block mb-3">
                                            Buy Now
                                        </button>
                                    </form>
                                @endguest
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="container-footer" style="margin-top: 40px">
            <div class="footer-2-3 container-xxl mx-auto position-relative p-0"
                style="font-family: 'Poppins', sans-serif ;">
                <div class="border-color info-footer">
                    <div class="">
                        <hr class="hr" />
                    </div>

                    <div class="mx-auto d-flex flex-column flex-lg-row align-items-center footer-info-space gap-4">
                        <div class="container pt-5 pb-4">
                            <div class="row justify-content-center">
                                <div class="col-12">
                                    <div class="col-12 col-lg-3">
                                        {{-- <h5 style="color: #FF0000">GET CONNECTED</h5> --}}
                                        <ul class="list-unstyled">
                                            <li><a href="#">Jakarta</a></li>
                                            <li><a href="#">Indonesia</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <nav class="d-flex flex-lg-row flex-column align-items-center justify-content-center">
                <p style="color:grey">Copyright © 2022 Eko Budiarto</p>
            </nav>
        </section>
    </div>
    @auth
        @php
        $carts = \App\Models\Cart::where('users_id', Auth::user()->id)->count();
        @endphp
    @endauth
    @guest
        @php
        $carts = 0;
        @endphp
    @endguest

@endsection

@section('fixed', 'd-none')

@push('addon-script')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })

        function addtocard(id) {
            var idproduct = $('.product-id').val();
            var addtocard = $('.addtocard').val();
            console.log(id)
            var Data = {
                id: idproduct,
                addtocard: "true",
            }
            console.log(idproduct)
            var addtocard = $.ajax({
                type: 'post',
                url: "/detail/" + idproduct,
                data: Data,
                dateType: "text",
                success: function(result) {
                    Toast.fire({
                        icon: 'success',
                        title: 'Berhasil Memasukan ke keranjang'
                    })
                    var carts = parseInt({{ $carts }})
                    $(".cart-badge").html(carts + 1)
                }
            })
            addtocard.error(function(e) {
                alert('salah')
            })
        }
    </script>
@endpush

@push('addon-style')
    <style>
        .categories .row img {
            border-radius: 5px 15px;
        }

        .carousel-inner .carousel-item img {
            object-fit: cover;
        }

        .categories .row .col-md-4 .title-categories {
            position: absolute;
            top: 82%;
            padding-left: 10px;
            font-size: 18px;
            line-height: 30px;
            font-weight: 400;
        }

        .categories .row .col-md-4 .title-text {
            position: absolute;
            top: 90%;
            padding-left: 10px;
            font-size: 14px;
            line-height: 30px;
            font-weight: 400;
        }

        .menus img {
            border-radius: 15px;
        }

        .title-menu {
            font-size: 18px;
        }

        .price-menu {
            position: absolute;
            right: 0;
            font-size: 12px;
            width: 40%;
        }

        .add-to-card {
            background-color: #dae0e5;
            border-color: #dae0e5;
        }
    </style>
@endpush
