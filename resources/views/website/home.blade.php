<!doctype html>
<html lang="en">
<!--

Page    : index / MobApp
Version : 1.0
Author  : Colorlib
URI     : https://colorlib.com

 -->

<head>
    <title>HappyDeliv - Empower Your Delivery Service</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="HappyDeliv - Empower Your Delivery Service">
    <meta name="keywords" content="delivery, happydeliv, tracking kiriman, kiriman, paket, jne, tiki, ekspedisi, logistik">

    <!-- Font -->
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css?family=Rubik:300,400,500" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ URL::asset('public/mobapp')}}/css/bootstrap.min.css">
    <!-- Themify Icons -->
    <link rel="stylesheet" href="{{ URL::asset('public/mobapp')}}/css/themify-icons.css">
    <!-- Owl carousel -->
    <link rel="stylesheet" href="{{ URL::asset('public/mobapp')}}/css/owl.carousel.min.css">
    <!-- Main css -->
    <link href="{{ URL::asset('public/mobapp')}}/css/style.css" rel="stylesheet">
    <link rel="icon" type="image/png"  href="{{ URL::asset('public/images')}}/favicon-fix.png">
</head>

<body data-spy="scroll" data-target="#navbar" data-offset="30">

    <!-- Nav Menu -->

    <div class="nav-menu fixed-top">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <nav class="navbar navbar-dark navbar-expand-lg">
                        <a class="navbar-brand" href="index.html"><img src="{{ URL::asset('public/mobapp')}}/images/logo.png" class="img-fluid" alt="logo"></a> <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span> </button>
                        <div class="collapse navbar-collapse" id="navbar">
                            <ul class="navbar-nav ml-auto">
                                <li class="nav-item"> <a class="nav-link active" href="#home">HOME <span class="sr-only">(current)</span></a> </li>
                                <li class="nav-item"> <a class="nav-link" href="#features">FITUR</a> </li>
                                <li class="nav-item"> <a class="nav-link" href="#gallery">GALERI</a> </li>
                                <li class="nav-item"> <a class="nav-link" href="#download">DOWNLOAD</a> </li>
                                <li class="nav-item"> <a class="nav-link" href="#contact">KONTAK</a> </li>
                                <li class="nav-item"> <a class="nav-link" href="http://happydeliv.com:4567/" target="_blank">API</a> </li>
                                <li class="nav-item"><a href="{{URL::to('/')}}/login" class="btn btn-outline-light my-3 my-sm-0 ml-lg-3">LOGIN</a></li>
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>


    <header class="bg-gradient" id="home">
        <div class="container mt-5">
            <h1>Empower Your Delivery Service</h1>
            <p class="tagline">Tak perlu repot-repot membuat dari nol. HappyDeliv menyediakan layanan yang memungkinkan perusahaan pengiriman barang memberikan fasilitas real-time tracking bagi para konsumen mereka. </p>
        </div>
        <div class="img-holder mt-3"><img src="{{ URL::asset('public/mobapp')}}/images/happydeliv_splash.png" alt="phone" class="img-fluid"></div>
    </header>


    <div class="section light-bg" id="features">


        <div class="container">

            <div class="section-title">
                {{-- <small>HIGHLIGHTS</small> --}}
                <h3>Fitur</h3>
            </div>


            <div class="row">
                <div class="col-12 col-lg-4">
                    <div class="card features">
                        <div class="card-body">
                            <div class="media">
                                {{-- <span class="ti-face-smile gradient-fill ti-3x mr-3"></span> --}}
                                <img src="{{ URL::asset('public/mobapp')}}/images/tracking.png" alt="image" class="ti-3x mr-3">
                                <div class="media-body">
                                    <h4 class="card-title">Real Time Tracking</h4>
                                    <p class="card-text">Real Time Tracking dapat memberikan kenyamanan dan pengalaman yang menyenangkan bagi penerima barang.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="card features">
                        <div class="card-body">
                            <div class="media">
                                {{-- <span class="ti-settings gradient-fill ti-3x mr-3"></span> --}}
                                <img src="{{ URL::asset('public/mobapp')}}/images/multiple.png" alt="image" class="ti-3x mr-3">
                                <div class="media-body">
                                    <h4 class="card-title">Multi Tracking</h4>
                                    <p class="card-text">Penerima kiriman dapat melakukan tracking beberapa paket  sekaligus meskipun dari perusahaan yang berbeda
 </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="card features">
                        <div class="card-body">
                            <div class="media">
                                {{-- <span class="ti-lock gradient-fill ti-3x mr-3"></span> --}}
                                <img src="{{ URL::asset('public/mobapp')}}/images/route.png" alt="image" class="ti-3x mr-3">
                                <div class="media-body">
                                    <h4 class="card-title">Best Route</h4>
                                    <p class="card-text">Kurir bisa mendapatkan
rekomendasi rute terbaik dalam
mengantarkan kiriman<br>&nbsp</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>



    </div>
    <!-- // end .section -->

    <div class="section light-bg">
        <div class="section-title">
            {{-- <small>Cara Kerja</small> --}}
            <h3>Cara Kerja HappyDeliv</h3>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-12 d-flex align-items-center">
                    <ul class="list-unstyled ui-steps">

                        <li class="media">
                            <div class="circle-icon mr-4">1</div>
                            <div class="media-body col-md-8">
                                <h5>&nbsp{{-- Create an Account --}}</h5>
                                <p>Pengirim mengirimkan 
barang dari kota asal
 </p>
                            </div>
                           
                        </li>
                        <li class="media my-4">
                            <div class="circle-icon mr-4">2</div>
                            <div class="media-body">
                                <h5>{{-- Share with friends --}}&nbsp</h5>
                                <p>Pegawai penerima paket menginputkan data kiriman ke dashboard HappyDeliv
</p>
                            </div>
                        </li>
                        <li class="media">
                            <div class="circle-icon mr-4">3</div>
                            <div class="media-body">
                                <h5>{{-- Enjoy your life --}}&nbsp</h5>
                                <p>Penerima kiriman akan mendapatkan SMS notifikasi yang berisi Track ID 
</p>
                            </div>
                        </li>
                        <li class="media">
                            <div class="circle-icon mr-4">4</div>
                            <div class="media-body">
                                <h5>{{-- Enjoy your life --}} &nbsp</h5>
                                
                                <p>Penerima kiriman memasukkan Track ID ke aplikasi HappyDeliv
</p>
                                
                            </div>
                        </li>
                        <li class="media">
                            <div class="circle-icon mr-4">5</div>
                            <div class="media-body">
                                {{-- <h5>Enjoy your life</h5> --}}
                                <h5>{{-- Enjoy your life --}} &nbsp</h5>
                                <p>Setelah barang sampai di kota tujuan. Kurir memulai pengantaran dan mengupdate status kiriman di aplikasi HappyDeliv
 </p>
                            </div>
                        </li>
                        <li class="media">
                            <div class="circle-icon mr-4">6</div>
                            <div class="media-body">
                                {{-- <h5>Enjoy your life</h5> --}}
                                <h5>{{-- Enjoy your life --}} &nbsp</h5>
                                <p>Penerima kiriman mendapatkan notifikasi dan bisa
memantau lokasi kiriman

 </p>
                            </div>
                        </li>
                    </ul>
                </div>
                

            </div>

        </div>

    </div>
    <!-- // end .section -->


    

    <div class="section light-bg" id="gallery">
        <div class="container">
            <div class="section-title">
                {{-- <small>GALLERY</small> --}}
                <h3>Screenshot Aplikasi</h3>
            </div>

            <div class="img-gallery owl-carousel owl-theme">
                <img src="{{ URL::asset('public/mobapp')}}/images/1._Splash_Screen.png" alt="image">
                <img src="{{ URL::asset('public/mobapp')}}/images/2._Login_Page.png" alt="image">
                <img src="{{ URL::asset('public/mobapp')}}/images/3._OTP_Page.png" alt="image">
                <img src="{{ URL::asset('public/mobapp')}}/images/3._Account_Detail.png" alt="image">
                <img src="{{ URL::asset('public/mobapp')}}/images/10.DetailPackage.png" alt="image">
                
                
                <img src="{{ URL::asset('public/mobapp')}}/images/6.In-ProgressWithWatchingList.png" alt="image">
                <img src="{{ URL::asset('public/mobapp')}}/images/9.DetailPaket-SetelahdiSetGPS.png" alt="image">
                <img src="{{ URL::asset('public/mobapp')}}/images/10.DetailPaket-SetelahOnProgress.png" alt="image">
                
            </div>

        </div>

    </div>
    <!-- // end .section -->


    <div class="section bg-gradient" id="download">
        <div class="container">
            <div class="call-to-action">

                <div class="box-icon"><span class="ti-mobile gradient-fill ti-3x"></span></div>
                <h2>Give It a Try</h2>
                <p class="tagline">Aplikasi HappyDeliv tersedia dalam 2 versi, yaitu app Android untuk kurir dan untuk End-User. App untuk kurir hanya bisa digunakan oleh kurir yang bekerja dibawah mitra HappyDeliv. Sedangkan App untuk End-User bebas digunakan oleh siapapun. </p>
                <div class="my-4">

                    <a href="https://drive.google.com/drive/folders/1mOUuHf-i21etryo7DLXxCr2S4cBNM4HM?usp=sharing" class="btn btn-light"><img width="100" src="{{ URL::asset('public/mobapp')}}/images/logo-kurir.png" alt="icon"> </a>
                    <a href="https://drive.google.com/drive/folders/1QOA67y-mi-_c2X-WA8gTJdp2Z_PRqrUB?usp=sharing" class="btn btn-light"><img width="100" src="{{ URL::asset('public/mobapp')}}/images/logo-end-user.png" alt="icon"> </a>
                </div>
                {{-- <p class="text-primary"><small><i>*Works on iOS 10.0.5+, Android Kitkat and above. </i></small></p> --}}
            </div>
        </div>

    </div>
    <!-- // end .section -->

    <div class="light-bg py-5" id="contact">
        <center><h2>Kontak</h2></center>
        <div class="container">
            <div class="row">

                <div class="col-lg-6 text-center text-lg-left">
                    <p class="mb-2">  <span class="ti-email mr-2"></span> <a class="mr-4" href="mailto:happydeliv@gmail.com">happydeliv@gmail.com</a></p>
                    <div class=" d-block d-sm-inline-block">
                        <p class="mb-2">
                            <span class="ti-headphone-alt mr-2"></span> <a href="tel:085742724990">085-742-724-990</a>
                        </p>
                    </div>
                    <div class="d-block d-sm-inline-block">
                        <p class="mb-0">
                            
                        </p>
                    </div>

                </div>
                {{-- <div class="col-lg-6">
                    <div class="social-icons">
                        <a href="#"><span class="ti-facebook"></span></a>
                        <a href="#"><span class="ti-twitter-alt"></span></a>
                        <a href="#"><span class="ti-instagram"></span></a>
                    </div>
                </div> --}}
            </div>

        </div>

    </div>
    <!-- // end .section -->
    <footer class="my-5 text-center">
        <!-- Copyright removal is not prohibited! -->
        <p class="mb-2"><small>COPYRIGHT © 2017. ALL RIGHTS RESERVED. MOBAPP TEMPLATE BY <a href="https://colorlib.com">COLORLIB</a></small></p>

        
    </footer>

    <!-- jQuery and Bootstrap -->
    <script src="{{ URL::asset('public/mobapp')}}/js/jquery-3.2.1.min.js"></script>
    <script src="{{ URL::asset('public/mobapp')}}/js/bootstrap.bundle.min.js"></script>
    <!-- Plugins JS -->
    <script src="{{ URL::asset('public/mobapp')}}/js/owl.carousel.min.js"></script>
    <!-- Custom JS -->
    <script src="{{ URL::asset('public/mobapp')}}/js/script.js"></script>

</body>

</html>
