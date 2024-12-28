{% extends "base.mvc" %}
{% block title %} Contact Us {% endblock %}
{% block body %}

<section class="py-5">
    <div class="container py-5">
        <div class="row mb-5">
            <div class="col-md-8 col-xl-6 text-center mx-auto">
                <p class="fw-bold text-success mb-2">Contacts</p>
                <h2 class="fw-bold">How you can reach us</h2>
            </div>
        </div>
        <div class="row d-flex justify-content-center">
            <div class="col-md-6 col-xl-4">
                <div>
                    <form class="p-3 p-xl-4" method="post" action="{{URL_ROOT}}/contact-us">
                        <div class="mb-3">
                            <input id="name-1" class="form-control {% echo !empty($errors->name) ? 'is-invalid' : ''; %}" type="text" name="name" value="{{$contact->name}}" placeholder="Name" />
                            <div class="invalid-feedback text-center">{{$errors->name}}</div>
                        </div>
                        <div class="mb-3">
                            <input id="email-1" class="form-control {% echo !empty($errors->email) ? 'is-invalid' : ''; %}" type="email" name="email" value="{{$contact->email}}" placeholder="Email" />
                            <div class="invalid-feedback text-center">{{$errors->email}}</div>
                        </div>
                        <div class="mb-3">
                            <input id="subject-1" class="form-control {% echo !empty($errors->subject) ? 'is-invalid' : ''; %}" type="text" name="subject" value="{{$contact->subject}}" placeholder="Subject" />
                            <div class="invalid-feedback text-center">{{$errors->subject}}</div>
                        </div>
                        <div class="mb-3">
                            <textarea id="message-1" class="form-control {% echo !empty($errors->message) ? 'is-invalid' : ''; %}" name="message" rows="6" placeholder="Message">{{$contact->message}}</textarea>
                            <div class="invalid-feedback text-center">{{$errors->message}}</div>
                        </div>
                        {% echo  $CSRF %}
                        <div>
                            <button class="btn btn-primary shadow d-block w-100" type="submit">Send </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-4 col-xl-4 d-flex justify-content-center justify-content-xl-start">
                <div class="d-flex flex-wrap flex-md-column justify-content-md-start align-items-md-start h-100">
                    <div class="d-flex align-items-center p-3">
                        <div class="bs-icon-md bs-icon-circle bs-icon-primary shadow d-flex flex-shrink-0 justify-content-center align-items-center d-inline-block bs-icon bs-icon-md">
                            <i class="bi bi-telephone"></i>
                        </div>
                        <div class="px-2">
                            <h6 class="fw-bold mb-0">Phone</h6>
                            <p class="text-muted mb-0">{{$_ENV['SITE_PHONE']}}</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center p-3">
                        <div class="bs-icon-md bs-icon-circle bs-icon-primary shadow d-flex flex-shrink-0 justify-content-center align-items-center d-inline-block bs-icon bs-icon-md">
                            <i class="bi bi-envelope"></i>
                        </div>
                        <div class="px-2">
                            <h6 class="fw-bold mb-0">Email</h6>
                            <p class="text-muted mb-0">{{$_ENV['SITE_EMAIL']}}</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center p-3">
                        <div class="bs-icon-md bs-icon-circle bs-icon-primary shadow d-flex flex-shrink-0 justify-content-center align-items-center d-inline-block bs-icon bs-icon-md">
                            <i class="bi bi-pin"></i>
                        </div>
                        <div class="px-2">
                            <h6 class="fw-bold mb-0">Location</h6>
                            <p class="text-muted mb-0">{{$_ENV['SITE_ADDRESS']}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
{% endblock %}

