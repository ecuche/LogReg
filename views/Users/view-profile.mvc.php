{% extends "users/users-base.mvc" %}

{% block title %} View Profile {% endblock %}
{% block body %}
<section class="py-5">
        <div class="container py-5">
            <div class="row d-flex justify-content-center">
                <div class="col-md-6 col-xl-4">
                    <div class="card">
                        <div class="card-body text-center d-flex flex-column align-items-center">
                        {% if(!empty($success)): %}
                            <div class="alert alert-success" role="alert">
                                {{$success}}
                            </div>
                        {% endif; %}
                        <div class="mb-3 row content-center">
                            <div class="card text-center row">
                                <div class="card-header">
                                    {{$user->name}}
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">{{$user->email}}</h5>
                                    <a href="{{$_ENV['URL_ROOT']}}/profile/update" class="btn btn-primary mb-3" class="btn btn-primary">Edit Profile</a>
                                    <a href="{{$_ENV['URL_ROOT']}}/update/password" class="btn btn-primary mb-3" class="btn btn-primary">Change Password</a>
                                </div>
                                <div class="card-footer text-muted">
                                    Joined: {{$time_ago}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
   
{% endblock %}

