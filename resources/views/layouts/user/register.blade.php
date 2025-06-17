@extends('index.clientdashboard')

@section('content')
    <div class="container">
        <div class="new-customers">
            <form action="#">
                <h6 class="widget-title border-left mb-50">NEW CUSTOMERS</h6>
                <div class="login-account p-30 box-shadow">
                    <div class="row">
                        <div class="col-sm-6">
                            <input type="text" placeholder="First Name">
                        </div>
                        <div class="col-sm-6">
                            <input type="text" placeholder="last Name">
                        </div>
                        <div class="col-sm-6">
                            <select class="custom-select">
                                <option value="defalt">country</option>
                                <option value="c-1">Australia</option>
                                <option value="c-2">Bangladesh</option>
                                <option value="c-3">Unitd States</option>
                                <option value="c-4">Unitd Kingdom</option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <select class="custom-select">
                                <option value="defalt">State</option>
                                <option value="c-1">Melbourne</option>
                                <option value="c-2">Dhaka</option>
                                <option value="c-3">New York</option>
                                <option value="c-4">London</option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <select class="custom-select">
                                <option value="defalt">Town/City</option>
                                <option value="c-1">Victoria</option>
                                <option value="c-2">Chittagong</option>
                                <option value="c-3">Boston</option>
                                <option value="c-4">Cambridge</option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" placeholder="Phone here...">
                        </div>
                    </div>
                    <input type="text" placeholder="Company neme here...">
                    <input type="text" placeholder="Email address here...">
                    <input type="password" placeholder="Password">
                    <input type="password" placeholder="Confirm Password">
                    <div class="checkbox">
                        <label class="mr-10">
                            <small>
                                <input type="checkbox" name="signup">Sign up for our newsletter!
                            </small>
                        </label>
                        <label>
                            <small>
                                <input type="checkbox" name="signup">Receive special offers from our
                                partners!
                            </small>
                        </label>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <button class="submit-btn-1 mt-20 btn-hover-1" type="submit" value="register">Register</button>
                        </div>
                        <div class="col-md-6">
                            <button class="submit-btn-1 mt-20 btn-hover-1 f-right" type="reset">Clear</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
