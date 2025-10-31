@extends('frontend.layout.main')
@section('extra_meta')
    <meta name="description" content="{{env('DESCRIPTION')}}">

    <!-- Google / Search Engine Tags -->
    <meta itemprop="name" content="{{env('APP_NAME', 'laravel')}}">
    <meta itemprop="description" content="{{env('DESCRIPTION')}}">
    <meta itemprop="image" content="{{env('APP_URL')}}">

    <!-- Facebook Meta Tags -->
    <meta property="og:url" content="{{env('APP_URL')}}">
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{env('APP_NAME', 'laravel')}}">
    <meta property="og:description" content="{{env('DESCRIPTION')}}">
    <meta property="og:image" content="{{env('LOGO_PATH')}}">

    <!-- Twitter Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{env('APP_NAME', 'laravel')}}">
    <meta name="twitter:description" content="{{env('DESCRIPTION')}}">
    <meta name="twitter:image" content="{{env('LOGO_PATH')}}">
@endsection
@section('content')
    <!-- Banner Area Starts -->
    <section class="banner-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 px-0">
                    <div class="banner-bg" style='background-image: url("{{asset('images/banner-bg.jpg')}}")'></div>
                </div>
                <div class="col-lg-6 align-self-center">
                    <div class="banner-text">
                        <h1 class="text-center">Making The Long Story of <span>Staffing</span> , Short.</h1>
                    </div>
                    <h1><hr class="hrhome"></h1>
                    <div class="banner-text">
                        {{-- <div class="d-flex justify-content-center">
                            <i class="fas fa-award fa-4x awardicon"></i>
                        </div>
                        <h1 class="text-center"> Join Our Clepius <span>Award</span> 2022</h1>

                        <a href="{{route('awardindex')}}" class="btn btn-light btn-block btn-lg font-weight-bolder"> More Information <i class="fas fa-arrow-right"></i></a> --}}
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Banner Area End -->

    <!-- Search Area Starts -->
    <div class="search-area">
        <div class="search-bg">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        @role(\App\Enums\RoleEnum::EMPLOYER)
                        <form action="{{route('employee_search')}}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-3 my-2">
                                    <select class="select2 job_titles" name="job_titles[]"  multiple="multiple">
                                        @foreach($job_titles as $title)
                                            <option value="{{$title->id}}">{{$title->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 my-2">
                                    <select class="select2 study_fields" name="study_fields[]" multiple="multiple">
                                        @foreach($study_fields as $study_field)
                                            <option value="{{$study_field->id}}">{{$study_field->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 my-2">
                                    <select class="select2 skills" name="skills[]" multiple="multiple">
                                        @foreach($skills as $skill)
                                            <option value="{{$skill->id}}">{{$skill->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 my-2">
                                    <select class="select2 countries" name="countries[]"  multiple="multiple">
                                        @foreach($countries as $country)
                                            <option value="{{$country->id}}">{{$country->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="d-block mx-auto template-btn">Find Employee</button>
                        </form>
                        @else
                            <form action="{{route("find_job")}}" method="post" class="d-md-flex justify-content-between">
                                @csrf
                                <select name="category_id">
                                    <option selected disabled>Select a Job Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{$category->id}}">{{$category->name}}</option>
                                    @endforeach
                                </select>
                                <select name="country_id">
                                    <option selected disabled>Select a Location</option>
                                    @foreach($countries as $country)
                                        <option value="{{$country->id}}">{{$country->name}}</option>
                                    @endforeach
                                </select>
                                <input name="keyword" type="text" placeholder="Search Keyword" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Search Keyword'">
                                <button type="submit" class="template-btn">find job</button>
                            </form>
                        @endrole
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Search Area End -->

    <!-- Category Area Starts -->
    <section class="category-area section-padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-top text-center">
                        <h2>Find job by Category</h2>
                        <p></p>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach($categories as $category)
                <div class="col-lg-3 col-md-6">
                    <a href="{{route('single_category',$category->slug)}}">
                        <div class="single-category text-center mb-4">
                            <img width="20%" src="{{$category->full_path}}" alt="category">
                            <h4>{{$category->name}}</h4>
                            <h5>{{$category->jobs()->where('is_open',1)->count()}} open job ,{{$category->jobs()->where('is_open',0)->count()}} closed job</h5>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- Category Area End -->

{{--    <!-- Jobs Area Starts -->--}}
{{--    <section class="jobs-area section-padding3">--}}
{{--        <div class="container">--}}
{{--            <div class="row">--}}
{{--                <div class="col-lg-12">--}}
{{--                    <div class="jobs-title">--}}
{{--                        <h2>Browse recent jobs</h2>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="row">--}}
{{--                <div class="col-lg-12">--}}
{{--                    <div class="jobs-tab tab-item">--}}
{{--                        <ul class="nav nav-tabs" id="myTab" role="tablist">--}}
{{--                            <li class="nav-item">--}}
{{--                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#recent" role="tab" aria-controls="home" aria-selected="true">recent</a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item">--}}
{{--                                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#full-time" role="tab" aria-controls="profile" aria-selected="false">full time</a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item">--}}
{{--                                <a class="nav-link" id="contact-tab" data-toggle="tab" href="#part-time" role="tab" aria-controls="part-time" aria-selected="false">part time</a>--}}
{{--                            </li>--}}
{{--                            <li class="nav-item">--}}
{{--                                <a class="nav-link" id="contact-tab2" data-toggle="tab" href="#intern" role="tab" aria-controls="intern" aria-selected="false">intern</a>--}}
{{--                            </li>--}}
{{--                        </ul>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="row">--}}
{{--                <div class="col-lg-12">--}}
{{--                    <div class="tab-content" id="myTabContent">--}}
{{--                        <div class="tab-pane fade show active" id="recent" role="tabpanel" aria-labelledby="home-tab">--}}
{{--                            <div class="single-job mb-4 d-lg-flex justify-content-between">--}}
{{--                                <div class="job-text">--}}
{{--                                    <h4>Assistant Executive - Production/ Quality Control</h4>--}}
{{--                                    <ul class="mt-4">--}}
{{--                                        <li class="mb-3"><h5><i class="fa fa-map-marker"></i> new yourk, USA</h5></li>--}}
{{--                                        <li class="mb-3"><h5><i class="fa fa-pie-chart"></i> Applied Chemistry & Chemical Engineering / Chemistry</h5></li>--}}
{{--                                        <li><h5><i class="fa fa-clock-o"></i> Deadline Deadline: Dec 11, 2018</h5></li>--}}
{{--                                    </ul>--}}
{{--                                </div>--}}
{{--                                <div class="job-img align-self-center">--}}
{{--                                    <img src="{{asset('images/job1.png')}}" alt="job">--}}
{{--                                </div>--}}
{{--                                <div class="job-btn align-self-center">--}}
{{--                                    <a href="#" class="third-btn job-btn1">full time</a>--}}
{{--                                    <a href="#" class="third-btn">apply</a>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="tab-pane fade" id="full-time" role="tabpanel" aria-labelledby="profile-tab">--}}
{{--                            <div class="single-job mb-4 d-lg-flex justify-content-between">--}}
{{--                                <div class="job-text">--}}
{{--                                    <h4>Asst. Manager, Production (Woven Dyeing)</h4>--}}
{{--                                    <ul class="mt-4">--}}
{{--                                        <li class="mb-3"><h5><i class="fa fa-map-marker"></i> new yourk, USA</h5></li>--}}
{{--                                        <li class="mb-3"><h5><i class="fa fa-pie-chart"></i> Applied Chemistry & Chemical Engineering / Chemistry</h5></li>--}}
{{--                                        <li><h5><i class="fa fa-clock-o"></i> Deadline Deadline: Dec 11, 2018</h5></li>--}}
{{--                                    </ul>--}}
{{--                                </div>--}}
{{--                                <div class="job-img align-self-center">--}}
{{--                                    <img src="{{asset('images/job2.png')}}" alt="job">--}}
{{--                                </div>--}}
{{--                                <div class="job-btn align-self-center">--}}
{{--                                    <a href="#" class="third-btn job-btn2">full time</a>--}}
{{--                                    <a href="#" class="third-btn">apply</a>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="tab-pane fade" id="part-time" role="tabpanel" aria-labelledby="contact-tab">--}}
{{--                            <div class="single-job mb-4 d-lg-flex justify-content-between">--}}
{{--                                <div class="job-text">--}}
{{--                                    <h4>Deputy Manager/ Assistant Manager - Footwear</h4>--}}
{{--                                    <ul class="mt-4">--}}
{{--                                        <li class="mb-3"><h5><i class="fa fa-map-marker"></i> new yourk, USA</h5></li>--}}
{{--                                        <li class="mb-3"><h5><i class="fa fa-pie-chart"></i> Applied Chemistry & Chemical Engineering / Chemistry</h5></li>--}}
{{--                                        <li><h5><i class="fa fa-clock-o"></i> Deadline Deadline: Dec 11, 2018</h5></li>--}}
{{--                                    </ul>--}}
{{--                                </div>--}}
{{--                                <div class="job-img align-self-center">--}}
{{--                                    <img src="{{asset('images/job3.png')}}" alt="job">--}}
{{--                                </div>--}}
{{--                                <div class="job-btn align-self-center">--}}
{{--                                    <a href="#" class="third-btn job-btn3">full time</a>--}}
{{--                                    <a href="#" class="third-btn">apply</a>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="tab-pane fade" id="intern" role="tabpanel" aria-labelledby="contact-tab2s">--}}
{{--                            <div class="single-job mb-4 d-lg-flex justify-content-between">--}}
{{--                                <div class="job-text">--}}
{{--                                    <h4>R&D Manager (Technical Lab Department)</h4>--}}
{{--                                    <ul class="mt-4">--}}
{{--                                        <li class="mb-3"><h5><i class="fa fa-map-marker"></i> new yourk, USA</h5></li>--}}
{{--                                        <li class="mb-3"><h5><i class="fa fa-pie-chart"></i> Applied Chemistry & Chemical Engineering / Chemistry</h5></li>--}}
{{--                                        <li><h5><i class="fa fa-clock-o"></i> Deadline Deadline: Dec 11, 2018</h5></li>--}}
{{--                                    </ul>--}}
{{--                                </div>--}}
{{--                                <div class="job-img align-self-center">--}}
{{--                                    <img src="{{asset('images/job4.png')}}" alt="job">--}}
{{--                                </div>--}}
{{--                                <div class="job-btn align-self-center">--}}
{{--                                    <a href="#" class="third-btn job-btn4">full time</a>--}}
{{--                                    <a href="#" class="third-btn">apply</a>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="more-job-btn mt-5 text-center">--}}
{{--                <a href="#" class="template-btn">more job post</a>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </section>--}}
{{--    <!-- Jobs Area End -->--}}

    <!-- Newsletter Area Starts -->
    <section class="newsletter-area section-padding" style="background-image: url('{{asset('images/rating-background.jpg')}}')">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-top text-center">
                        <h2>Rate Our Platform</h2>
                        <p>Give us your opinion about the platform</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <form id="review-frm" method="POST" action="{{route('review')}}">
                        @csrf
                        <div class="rating">
                            <label>
                                <input type="radio" name="rate" value="1" />
                                <span class="icon">★</span>
                            </label>
                            <label>
                                <input type="radio" name="rate" value="2" />
                                <span class="icon">★</span>
                                <span class="icon">★</span>
                            </label>
                            <label>
                                <input type="radio" name="rate" value="3" />
                                <span class="icon">★</span>
                                <span class="icon">★</span>
                                <span class="icon">★</span>
                            </label>
                            <label>
                                <input type="radio" name="rate" value="4" />
                                <span class="icon">★</span>
                                <span class="icon">★</span>
                                <span class="icon">★</span>
                                <span class="icon">★</span>
                            </label>
                            <label>
                                <input type="radio" name="rate" value="5" />
                                <span class="icon">★</span>
                                <span class="icon">★</span>
                                <span class="icon">★</span>
                                <span class="icon">★</span>
                                <span class="icon">★</span>
                            </label>
                        </div>
                        <br>
                        <input type="hidden" name="user_id" value="{{auth()->user()->id??null}}">
                        <input type="text" maxlength="190" name="message" placeholder="Your message here" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Your message here'" required>
                        <button type="submit" id="send-review"class="template-btn">Review</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- Newsletter Area End -->

    @if(count($partners) > 0)
        <!-- News Area Starts -->
        <section id="partners" class="news-area section-padding3">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-top text-center">
                            <h2>Our Trusted Partners</h2>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    @foreach($partners as $partner)
                        <div class="col-md-3 justify-content-center">
                            <a href="{{$partner->link}}" target="_blank">
                                <img height="100px" class="d-block mx-auto text-center partner-image" src="{{$partner->full_path}}" alt="{{$partner->name}}">
                            </a>
                            <h4 class="mt-3 text-center">{{$partner->name}}</h4>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
        <!-- News Area End -->
    @endif

    @if(count($employee_reviews) > 0)
    <!-- Employee Area Starts -->
    <section class="employee-area section-padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-top text-center">
                        <h2>Latest Happy Employee</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="employee-slider owl-carousel">
                        @foreach($employee_reviews as $review)
                        <div class="single-slide d-sm-flex">
                            <div class="slide-img" style="background-image: url('{{$review->user->image ? asset('storage')."/".$review->user->image : asset('images/cleipus-unkowon-profile.png')}}')">
                                <div class="hover-state">
                                    <div class="hover-text text-center">
                                        <h3>{{$review->user->first_name . " " .$review->user->last_name}}</h3>
                                        <h5>
                                            @foreach(range(1 ,5) as $star)
                                                @if($star <= $review->rate)
                                                    <span class="filled-star">★</span>
                                                @else
                                                    <span class="empty-star">★</span>
                                                @endif
                                            @endforeach
                                        </h5>
                                    </div>
                                </div>
                            </div>
                            <div class="slide-text align-self-center">
                                <i class="fa fa-quote-left"></i>
                                <p>{{$review->message}}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Employee Area End -->
    @endif

    @if(count($company_reviews) > 0)
    <!-- Employee Area Starts -->
    <section class="employee-area section-padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-top text-center">
                        <h2>Latest Happy Employer</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="employee-slider owl-carousel">
                        @foreach($company_reviews as $review)
                        <div class="single-slide d-sm-flex">
                            <div class="slide-img" style="background-image: url('{{$review->user->image ? asset('storage').'/'.$review->user->image : asset('images/cleipus-unkowon-profile.png')}}')">
                                <div class="hover-state">
                                    <div class="hover-text text-center">
                                        <h3>{{$review->user->first_name . " " .$review->user->last_name}}</h3>
                                        <h5>
                                            @foreach(range(1 ,5) as $star)
                                                @if($star <= $review->rate)
                                                    <span class="filled-star">★</span>
                                                @else
                                                    <span class="empty-star">★</span>
                                                @endif
                                            @endforeach
                                        </h5>
                                    </div>
                                </div>
                            </div>
                            <div class="slide-text align-self-center">
                                <i class="fa fa-quote-left"></i>
                                <p>{{$review->message}}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Employee Area End -->
    @endif
    @if(count($blogs) > 0)
    <!-- News Area Starts -->
    <section id="blog" class="news-area section-padding3">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-top text-center">
                        <h2>Latest News</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach($blogs as $blog)
                <div class="col-lg-4 col-md-6">
                    <div class="single-news mb-5 mb-lg-0">
                        <a href="{{route("single_blog",$blog->slug)}}">
                        <div class="news-img" style="background-image: url('{{asset($blog->full_path)}}')"></div>
                        </a>
                        <div class="news-title py-4 text-center">
                            <h4><a href="{{route("single_blog",$blog->slug)}}">{{$blog->title}}</a></h4>
                            <small class="text-center"><strong>Published on : </strong>{{\Carbon\Carbon::parse($blog->created_at)->format("d-M-Y")}}</small>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- News Area End -->
    @endif
    <!-- News Area Starts -->
    @if(count($events) > 0)
    <section id="blog" class="news-area section-padding3">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-top text-center">
                        <h2>Latest Events</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach($events as $event)
                    <div class="col-lg-4 col-md-6">
                        <div class="single-news mb-5 mb-lg-0">
                            <a href="{{route("single_event",$event->slug)}}">
                            <div class="news-img" style="background-image: url('{{$event->full_path}}')"></div>
                            </a>
                            <div class="news-title py-4 text-center">
                                <h4><a href="{{route("single_event",$event->slug)}}">{{$event->title}}</a></h4>
                                <p class="text-center"><strong>Started : </strong>{{\Carbon\Carbon::parse($event->event_date)->format("d-M-Y")}}</p>
                                <p class="text-center"><strong>Location : </strong>{{$event->location}}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
    <!-- News Area End -->
@endsection
@push('scripts')
    <script>
        $(function () {
            $(".select2.job_titles").select2({
                placeholder: "Select many Job Title",
                width:'100%',
            });
            $(".select2.study_fields").select2({
                placeholder: "Select many Study Field",
                width:'100%',
            });
            $(".select2.skills").select2({
                placeholder: "Select many Skill",
                width:'100%',
            });
            $(".select2.countries").select2({
                placeholder: "Select many Counties",
                width:'100%',
            });
        })
        $(':radio').change(function() {
            console.log('New star rating: ' + this.value);
        });
        $('#send-review').on('click',function (e) {
            e.preventDefault();
            $('#review-frm').submit()
        })
        $('#review-frm').on('submit',function (e) {
            e.preventDefault();
            let formData = $(this).serialize();
            $.ajax({
                url:"{{route('review')}}",
                data:formData,
                method:'POST',
                success(data){
                    if(data.success === true){
                        new swal(
                            'success',
                            data.message,
                            'success',
                        );
                    } else{
                        new swal(
                            'warning',
                            data.message,
                            'warning',
                        );
                    }
                }
            })
        })
    </script>
@endpush
