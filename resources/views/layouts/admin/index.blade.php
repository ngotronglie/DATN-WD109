@extends('index.admindashboard')
@section('content')

               <!-- Start Container Fluid -->
               <div class="container-fluid">

                    

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
                                                       <td><a href="#" class="text-muted">dashboard.html</a></td>
                                                       <td> 4265</td>
                                                       <td>09m:45s</td>
                                                       <td><span class="badge badge-soft-danger">20.4%</span></td>
                                                  </tr>
                                                  <tr>
                                                       <td><a href="#" class="text-muted">chat.html</a></td>
                                                       <td>2584 </td>
                                                       <td>05m:02s</td>
                                                       <td><span class="badge badge-soft-warning">12.25%</span></td>
                                                  </tr>
                                                  <tr>
                                                       <td><a href="#" class="text-muted">auth-login.html</a></td>
                                                       <td> 3369</td>
                                                       <td>04m:25s</td>
                                                       <td><span class="badge badge-soft-success">5.2%</span></td>
                                                  </tr>
                                                  <tr>
                                                       <td><a href="#" class="text-muted">email.html</a></td>
                                                       <td>985 </td>
                                                       <td>02m:03s</td>
                                                       <td><span class="badge badge-soft-danger">64.2%</span></td>
                                                  </tr>
                                                  <tr>
                                                       <td><a href="#" class="text-muted">social.html</a></td>
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
