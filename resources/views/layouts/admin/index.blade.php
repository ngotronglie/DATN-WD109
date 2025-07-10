@extends('index.admindashboard')
@section('content')

               <!-- Start Container Fluid -->
               <div class="container-fluid">

                    <!-- Start here.... -->

                    <div class="row">
                         <div class="col-md-6 col-xl-3">
                              <div class="card">
                                   <div class="card-body">
                                        <div class="row">
                                             <div class="col-6">
                                                  <div class="avatar-md bg-light bg-opacity-50 rounded">
                                                       <iconify-icon icon="solar:leaf-bold-duotone" class="fs-32 text-success avatar-title"></iconify-icon>
                                                  </div>
                                             </div> <!-- end col -->
                                             <div class="col-6 text-end">
                                                  <p class="text-muted mb-0 text-truncate">Page View</p>
                                                  <h3 class="text-dark mt-1 mb-0">13, 647</h3>
                                             </div> <!-- end col -->
                                        </div> <!-- end row-->
                                   </div> <!-- end card body -->
                                   <div class="card-footer border-0 py-2 bg-light bg-opacity-50">
                                        <div class="d-flex align-items-center justify-content-between">
                                             <div>
                                                  <span class="text-success"> <i class="bx bxs-up-arrow fs-12"></i> 2.3%</span>
                                                  <span class="text-muted ms-1 fs-12">Last Month</span>
                                             </div>
                                             <a href="#!" class="text-reset fw-semibold fs-12">View More</a>
                                        </div>
                                   </div> <!-- end card body -->
                              </div> <!-- end card -->
                         </div> <!-- end col -->
                         <div class="col-md-6 col-xl-3">
                              <div class="card">
                                   <div class="card-body">
                                        <div class="row">
                                             <div class="col-6">
                                                  <div class="avatar-md bg-light bg-opacity-50 rounded">
                                                       <iconify-icon icon="solar:cpu-bolt-line-duotone" class="fs-32 text-success avatar-title"></iconify-icon>
                                                  </div>
                                             </div> <!-- end col -->
                                             <div class="col-6 text-end">
                                                  <p class="text-muted mb-0 text-truncate">Clicks</p>
                                                  <h3 class="text-dark mt-1 mb-0">9, 526</h3>
                                             </div> <!-- end col -->
                                        </div> <!-- end row-->
                                   </div> <!-- end card body -->
                                   <div class="card-footer border-0 py-2 bg-light bg-opacity-50">
                                        <div class="d-flex align-items-center justify-content-between">
                                             <div>
                                                  <span class="text-success"> <i class="bx bxs-up-arrow fs-12"></i> 8.1%</span>
                                                  <span class="text-muted ms-1 fs-12">Last Month</span>
                                             </div>
                                             <a href="#!" class="text-reset fw-semibold fs-12">View More</a>
                                        </div>
                                   </div> <!-- end card body -->
                              </div> <!-- end card -->
                         </div> <!-- end col -->
                         <div class="col-md-6 col-xl-3">
                              <div class="card">
                                   <div class="card-body">
                                        <div class="row">
                                             <div class="col-6">
                                                  <div class="avatar-md bg-light bg-opacity-50 rounded">
                                                       <iconify-icon icon="solar:layers-bold-duotone" class="fs-32 text-success avatar-title"></iconify-icon>
                                                  </div>
                                             </div> <!-- end col -->
                                             <div class="col-6 text-end">
                                                  <p class="text-muted mb-0 text-truncate">Conversions</p>
                                                  <h3 class="text-dark mt-1 mb-0">976</h3>
                                             </div> <!-- end col -->
                                        </div> <!-- end row-->
                                   </div> <!-- end card body -->
                                   <div class="card-footer border-0 py-2 bg-light bg-opacity-50">
                                        <div class="d-flex align-items-center justify-content-between">
                                             <div>
                                                  <span class="text-danger"> <i class="bx bxs-down-arrow fs-12"></i> 0.3%</span>
                                                  <span class="text-muted ms-1 fs-12">Last Month</span>
                                             </div>
                                             <a href="#!" class="text-reset fw-semibold fs-12">View More</a>
                                        </div>
                                   </div> <!-- end card body -->
                              </div> <!-- end card -->
                         </div> <!-- end col -->
                         <div class="col-md-6 col-xl-3">
                              <div class="card">
                                   <div class="card-body">
                                        <div class="row">
                                             <div class="col-6">
                                                  <div class="avatar-md bg-light bg-opacity-50 rounded">
                                                       <iconify-icon icon="solar:users-group-two-rounded-bold-duotone" class="fs-32 text-success avatar-title"></iconify-icon>
                                                  </div>
                                             </div> <!-- end col -->
                                             <div class="col-6 text-end">
                                                  <p class="text-muted mb-0 text-truncate">New Users</p>
                                                  <h3 class="text-dark mt-1 mb-0">$123.6k</h3>
                                             </div> <!-- end col -->
                                        </div> <!-- end row-->
                                   </div> <!-- end card body -->
                                   <div class="card-footer border-0 py-2 bg-light bg-opacity-50">
                                        <div class="d-flex align-items-center justify-content-between">
                                             <div>
                                                  <span class="text-danger"> <i class="bx bxs-down-arrow fs-12"></i> 10.6%</span>
                                                  <span class="text-muted ms-1 fs-12">Last Month</span>
                                             </div>
                                             <a href="#!" class="text-reset fw-semibold fs-12">View More</a>
                                        </div>
                                   </div> <!-- end card body -->
                              </div> <!-- end card -->
                         </div> <!-- end col -->
                    </div> <!-- end row -->

                    <div class="row">
                         <div class="col">
                              <div class="card">
                                   <div class="card-body p-0">
                                        <div class="row g-0">
                                             <div class="col-lg-3">
                                                  <div class="p-3">
                                                       <h5 class="card-title">Conversions</h5>
                                                       <div id="conversions" class="apex-charts mb-2 mt-n2"></div>
                                                       <div class="row text-center">
                                                            <div class="col-6">
                                                                 <p class="text-muted mb-2">This Week</p>
                                                                 <h3 class="text-dark mb-3">23.5k</h3>
                                                            </div> <!-- end col -->
                                                            <div class="col-6">
                                                                 <p class="text-muted mb-2">Last Week</p>
                                                                 <h3 class="text-dark mb-3">41.05k</h3>
                                                            </div> <!-- end col -->
                                                       </div> <!-- end row -->
                                                       <div class="text-center">
                                                            <button type="button" class="btn btn-light shadow-none w-100">View Details</button>
                                                       </div> <!-- end row -->
                                                  </div>
                                             </div> <!-- end left chart card -->
                                             <div class="col-lg-6 border-start border-end">
                                                  <div class="p-3">
                                                       <div class="d-flex justify-content-between align-items-center">
                                                            <h4 class="card-title">Performance</h4>
                                                            <div>
                                                                 <button type="button" class="btn btn-sm btn-outline-light">ALL</button>
                                                                 <button type="button" class="btn btn-sm btn-outline-light">1M</button>
                                                                 <button type="button" class="btn btn-sm btn-outline-light">6M</button>
                                                                 <button type="button" class="btn btn-sm btn-outline-light active">1Y</button>
                                                            </div>
                                                       </div> <!-- end card-title-->

                                                       <div class="alert alert-warning mt-3 text text-truncate mb-0" role="alert">
                                                            We regret to inform you that our server is currently experiencing technical difficulties.
                                                       </div>

                                                       <div dir="ltr">
                                                            <div id="dash-performance-chart" class="apex-charts"></div>
                                                       </div>
                                                  </div>
                                             </div> <!-- end right chart card -->

                                             <div class="col-lg-3">
                                                  <h5 class="card-title p-3">Session By Browser</h5>
                                                  <div class="px-3" data-simplebar style="max-height: 310px;">
                                                       <div class="d-flex justify-content-between align-items-center p-2">
                                                            <span class="align-middle fw-medium">Chrome</span>
                                                            <span class="fw-semibold text-muted">62.5%</span>
                                                            <span class="fw-semibold text-muted">5.06k</span>
                                                       </div>

                                                       <div class="d-flex justify-content-between align-items-center p-2">
                                                            <span class="align-middle fw-medium">Firefox</span>
                                                            <span class="fw-semibold text-muted">12.3%</span>
                                                            <span class="fw-semibold text-muted">1.5k</span>
                                                       </div>

                                                       <div class="d-flex justify-content-between align-items-center p-2">
                                                            <span class="align-middle fw-medium">Safari</span>
                                                            <span class="fw-semibold text-muted">9.86%</span>
                                                            <span class="fw-semibold text-muted">1.03k</span>
                                                       </div>

                                                       <div class="d-flex justify-content-between align-items-center p-2">
                                                            <span class="align-middle fw-medium">Brave</span>
                                                            <span class="fw-semibold text-muted">3.15%</span>
                                                            <span class="fw-semibold text-muted">0.3k</span>
                                                       </div>

                                                       <div class="d-flex justify-content-between align-items-center p-2">
                                                            <span class="align-middle fw-medium">Opera</span>
                                                            <span class="fw-semibold text-muted">3.01%</span>
                                                            <span class="fw-semibold text-muted">1.58k</span>
                                                       </div>

                                                       <div class="d-flex justify-content-between align-items-center p-2">
                                                            <span class="align-middle fw-medium">Falkon</span>
                                                            <span class="fw-semibold text-muted">2.8%</span>
                                                            <span class="fw-semibold text-muted">0.01k</span>
                                                       </div>

                                                       <div class="d-flex justify-content-between align-items-center p-2">
                                                            <span class="align-middle fw-medium">Web</span>
                                                            <span class="fw-semibold text-muted">1.05%</span>
                                                            <span class="fw-semibold text-muted">2.51k</span>
                                                       </div>

                                                       <div class="d-flex justify-content-between align-items-center p-2">
                                                            <span class="align-middle fw-medium">Other</span>
                                                            <span class="fw-semibold text-muted">6.38%</span>
                                                            <span class="fw-semibold text-muted">3.6k</span>
                                                       </div>
                                                  </div>
                                                  <div class="text-center p-3">
                                                       <button type="button" class="btn btn-light shadow-none w-100">View All</button>
                                                  </div> <!-- end row -->
                                             </div>
                                        </div> <!-- end chart card -->
                                   </div> <!-- end card body -->
                              </div> <!-- end card -->
                         </div> <!-- end col -->
                    </div> <!-- end row -->

                    <div class="row">
                         <div class="col-lg-6">
                              <div class="card">
                                   <div class="d-flex card-header justify-content-between align-items-center border-bottom border-dashed">
                                        <h4 class="card-title">Sessions by Country</h4>
                                        <div class="dropdown">
                                             <a href="#" class="dropdown-toggle btn btn-sm btn-outline-light" data-bs-toggle="dropdown" aria-expanded="false">
                                                  View Data
                                             </a>
                                             <div class="dropdown-menu dropdown-menu-end">
                                                  <!-- item-->
                                                  <a href="javascript:void(0);" class="dropdown-item">Download</a>
                                                  <!-- item-->
                                                  <a href="javascript:void(0);" class="dropdown-item">Export</a>
                                                  <!-- item-->
                                                  <a href="javascript:void(0);" class="dropdown-item">Import</a>
                                             </div>
                                        </div>
                                   </div>

                                   <div class="card-body pt-0">
                                        <div class="row align-items-center">
                                             <div class="col-lg-7">
                                                  <div id="world-map-markers" class="mt-3" style="height: 220px">
                                                  </div>
                                             </div>
                                             <div class="col-lg-5" dir="ltr">
                                                  <div class="p-3 pb-0">
                                                       <!-- Country Data -->
                                                       <div class="d-flex justify-content-between align-items-center">
                                                            <p class="mb-1">
                                                                 <iconify-icon icon="circle-flags:us" class="fs-16 align-middle me-1"></iconify-icon> <span class="align-middle">United States</span>
                                                            </p>
                                                       </div>
                                                       <div class="row align-items-center mb-3">
                                                            <div class="col">
                                                                 <div class="progress progress-soft progress-sm">
                                                                      <div class="progress-bar bg-secondary" role="progressbar" style="width: 82.05%" aria-valuenow="" aria-valuemin="0" aria-valuemax="100"></div>
                                                                 </div>
                                                            </div>
                                                            <div class="col-auto">
                                                                 <p class="mb-0 fs-13 fw-semibold">659k</p>
                                                            </div>
                                                       </div>

                                                       <!-- Country Data -->
                                                       <div class="d-flex justify-content-between align-items-center">
                                                            <p class="mb-1">
                                                                 <iconify-icon icon="circle-flags:ru" class="fs-16 align-middle me-1"></iconify-icon> <span class="align-middle">Russia</span>
                                                            </p>
                                                       </div>
                                                       <div class="row align-items-center mb-3">
                                                            <div class="col">
                                                                 <div class="progress progress-soft progress-sm">
                                                                      <div class="progress-bar bg-info" role="progressbar" style="width: 70.5%" aria-valuenow="" aria-valuemin="0" aria-valuemax="100"></div>
                                                                 </div>
                                                            </div>
                                                            <div class="col-auto">
                                                                 <p class="mb-0 fs-13 fw-semibold">485k</p>
                                                            </div>
                                                       </div>

                                                       <!-- Country Data -->
                                                       <div class="d-flex justify-content-between align-items-center">
                                                            <p class="mb-1">
                                                                 <iconify-icon icon="circle-flags:cn" class="fs-16 align-middle me-1"></iconify-icon> <span class="align-middle">China</span>
                                                            </p>
                                                       </div>
                                                       <div class="row align-items-center mb-3">
                                                            <div class="col">
                                                                 <div class="progress progress-soft progress-sm">
                                                                      <div class="progress-bar bg-warning" role="progressbar" style="width: 65.8%" aria-valuenow="" aria-valuemin="0" aria-valuemax="100"></div>
                                                                 </div>
                                                            </div>
                                                            <div class="col-auto">
                                                                 <p class="mb-0 fs-13 fw-semibold">355k</p>
                                                            </div>
                                                       </div>

                                                       <!-- Country Data -->
                                                       <div class="d-flex justify-content-between align-items-center">
                                                            <p class="mb-1">
                                                                 <iconify-icon icon="circle-flags:ca" class="fs-16 align-middle me-1"></iconify-icon> <span class="align-middle">Canada</span>
                                                            </p>
                                                       </div>
                                                       <div class="row align-items-center">
                                                            <div class="col">
                                                                 <div class="progress progress-soft progress-sm">
                                                                      <div class="progress-bar bg-success" role="progressbar" style="width: 55.8%" aria-valuenow="" aria-valuemin="0" aria-valuemax="100"></div>
                                                                 </div>
                                                            </div>
                                                            <div class="col-auto">
                                                                 <p class="mb-0 fs-13 fw-semibold">204k</p>
                                                            </div>
                                                       </div>
                                                  </div>
                                             </div>
                                        </div>
                                   </div> <!-- end card-body-->
                              </div> <!-- end card-->
                         </div> <!-- end col-->

                         <div class="col-lg-6">
                              <div class="card card-height-100">
                                   <div class="card-header d-flex align-items-center justify-content-between gap-2">
                                        <h4 class="card-title flex-grow-1">Top Pages</h4>
                                        <div>
                                             <a href="#" class="btn btn-sm btn-soft-primary">View All</a>
                                        </div>
                                   </div>
                                   <div class="table-responsive">
                                        <table class="table table-hover table-nowrap table-centered m-0">
                                             <thead class="bg-light bg-opacity-50">
                                                  <tr>
                                                       <th class="text-muted py-1">Page Path</th>
                                                       <th class="text-muted py-1">Page Views</th>
                                                       <th class="text-muted py-1">Avg Time on Page</th>
                                                       <th class="text-muted py-1">Exit Rate</th>
                                                  </tr>
                                             </thead>
                                             <tbody>
                                                  <tr>
                                                       <td><a href="#" class="text-muted">rasket/dashboard.html</a></td>
                                                       <td> 4265</td>
                                                       <td>09m:45s</td>
                                                       <td><span class="badge badge-soft-danger">20.4%</span></td>
                                                  </tr>
                                                  <tr>
                                                       <td><a href="#" class="text-muted">rasket/chat.html</a></td>
                                                       <td>2584 </td>
                                                       <td>05m:02s</td>
                                                       <td><span class="badge badge-soft-warning">12.25%</span></td>
                                                  </tr>
                                                  <tr>
                                                       <td><a href="#" class="text-muted">rasket/auth-login.html</a></td>
                                                       <td> 3369</td>
                                                       <td>04m:25s</td>
                                                       <td><span class="badge badge-soft-success">5.2%</span></td>
                                                  </tr>
                                                  <tr>
                                                       <td><a href="#" class="text-muted">rasket/email.html</a></td>
                                                       <td>985 </td>
                                                       <td>02m:03s</td>
                                                       <td><span class="badge badge-soft-danger">64.2%</span></td>
                                                  </tr>
                                                  <tr>
                                                       <td><a href="#" class="text-muted">rasket/social.html</a></td>
                                                       <td>653 </td>
                                                       <td>15m:56s</td>
                                                       <td><span class="badge badge-soft-success">2.4%</span></td>
                                                  </tr>
                                             </tbody>
                                        </table>
                                   </div>
                              </div>
                         </div>
                    </div> <!-- end row-->

               </div>
               <!-- End Container Fluid -->
               @yield('script')

@endsection
