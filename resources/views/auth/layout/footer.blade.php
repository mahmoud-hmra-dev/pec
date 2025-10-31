
<style>
    /*--------------------------------------------------------------
# Footer
--------------------------------------------------------------*/
    .footer {
        font-size: 14px;
        background-color: #2E4049;
        padding-top: 50px;
        color: white;
    }

    .footer .footer-info .logo {
        line-height: 0;
        margin-bottom: 25px;
    }

    .footer .footer-info .logo img {
        max-height: 150px;
        margin-right: 6px;
    }

    .footer .footer-info .logo span {
        font-size: 30px;
        font-weight: 700;
        letter-spacing: 1px;
        color: #fff;
        font-family: "Work Sans", sans-serif;
    }

    .footer .footer-info p {
        font-size: 14px;
        font-family: "Work Sans", sans-serif;
        padding: 50px 10px;
        text-align: justify;

    }
    .footer .footer-info .help {
        padding: 15px 0px;
        width: 90%;
        background: #F6D55C;
        border-radius: 10px;
        font-family: 'Work Sans', sans-serif;
        font-style: normal;
        font-weight: 500;
        font-size: 23px;
        line-height: 32px;
        text-align: center;
        text-transform: capitalize;
        color: #FFFFFF;

    }
    .footer .social-links a {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        font-size: 16px;
        color: rgba(255, 255, 255, 0.7);
        margin-right: 10px;
        transition: 0.3s;
    }
    .footer .social-links a img {
        width: 30px;
        height: 30px;
    }

    .footer .social-links a:hover {
        color: #fff;
        border-color: #fff;
    }

    .footer h4 {
        font-size: 16px;
        font-weight: bold;
        position: relative;
        padding-bottom: 12px;
    }

    .footer .footer-links {
        margin-bottom: 30px;
        display: flex;
        justify-content: center;
        align-items: center;

        background: #587381;
        border-radius: 18px;
        padding: 40px;
    }

    .footer .footer-links ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer .footer-links ul i {
        padding-right: 2px;
        color: rgba(0, 131, 116, 0.8);
        font-size: 12px;
        line-height: 0;
    }

    .footer .footer-links ul li {
        padding: 10px 0;
        display: flex;
        align-items: center;
    }

    .footer .footer-links ul li:first-child {
        padding-top: 0;
    }

    .footer .footer-links ul a {
        color: rgba(255, 255, 255, 0.7);
        transition: 0.3s;
        display: inline-block;
        padding: 9px 3px;
        width: 100px;
        font-family: 'Work Sans', sans-serif;
        font-style: normal;
        font-weight: 500;
        font-size: 14px;
        line-height: 16px;
        text-transform: capitalize;
        color: #F1EEEC;
    }



    .footer .footer-links ul a:hover {
        color: #fff;
    }

    .footer .footer-contact p {
        line-height: 26px;
    }

    .footer .copyright {
        text-align: center;
    }

    .footer .credits {
        padding-top: 4px;
        text-align: center;
        font-size: 13px;
    }
    .footer .footer-bottom {
        padding: 15px 0;
    }
    .footer .footer-bottom .mail a{
        color: #FFFFFF;
    }
    .footer .credits a {
        color: #fff;
    }
</style>
<footer id="footer" class="footer">
    <div class="container">
        <div class="row d-flex align-items-center">
            <div class="col-md-5">
                <div class="footer-info">
                    <div class="d-flex align-items-center">
                        <a href="index.html" class="logo">
                            <img src="{{asset('images/footer-logo.svg')}}">
                        </a>
                        <p>Konzola makes it easy and safe for you to give to local projects anywhere in the world, while providing nonprofits with the tools, training, and support they need to thrive.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="footer-links">
                    <ul class="list-unstyled">
                        <div class="row">
                            <div class="col-4">
                                <li><a href="#">About</a></li>
                            </div>
                            <div class="col-4">
                                <li><a href="#">Nonprofits</a></li>
                            </div>
                            <div class="col-4">
                                <li><a href="#">Corporate</a></li>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <li><a href="#">Donations</a></li>
                            </div>
                            <div class="col-4">
                                <li><a href="#">Silent Auctions</a></li>
                            </div>
                            <div class="col-4">
                                <li><a href="#">contact us</a></li>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <li><a href="#">Learn</a></li>
                            </div>
                            <div class="col-4">
                                <li><a href="#">Raffles</a></li>
                            </div>
                        </div>
                    </ul>
                </div>
            </div>





            <div class="col-md-2">
                <div class="footer-info d-flex align-content-center justify-content-evenly">
                    <a href="#" class="help">help center</a>
                </div>

                <div class="footer-info">
                    <div class="social-links d-flex mt-4 d-flex align-content-center justify-content-evenly">
                        <a href="#" class="facebook"><img src="{{asset('fronted/img/icons/fb.svg')}}"></a>
                        <a href="#" class="instagram"><img src="{{asset('fronted/img/icons/insta.svg')}}"></a>
                        <a href="#" class="linkedin"><img src="{{asset('fronted/img/icons/twitterx.svg')}}"></a>
                    </div>
                </div>
            </div>
        </div>
    </div>





    <hr class="">

    <div class="container footer-bottom" >
        <div class="row align-content-center">
            <div class="col-md-6 mail">

                <div class="icon-box align-items-center text-sm-center">
                    <img src="{{ asset('fronted/img/social/mail.svg') }}" class="mailbox">
                    <a href="#" style="margin-left: 5px;">emailinfo@outlook.com</a>
                </div>
            </div>
            <div class="col-md-6 credits" style="margin-left: auto;">
                <a href="#">I agree to the Terms of Service and Privacy Policy.</a>
            </div>
        </div>

    </div>
</footer>
<div id="preloader"></div>
