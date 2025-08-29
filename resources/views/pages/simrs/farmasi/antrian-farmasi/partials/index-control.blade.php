<style>
    .top-button-div {
        margin-left: 12px;
        border-radius: 8px;
        padding: 2px;
        /* creates the "border" thickness */
        background: linear-gradient(to right, #5f9fff, #2f00ff);
        /* the fake border */
        position: relative;
        display: inline-block;
        /* shrink to fit if needed */
    }

    .top-button-div>.inner {
        background: white;
        /* this looks like the real background */
        border-radius: 8px;
        /* slightly less than outer */
        padding: 2px;
    }
</style>

<div class="row justify-content-center">
    <div class="col-xl-8">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    Control Panel <span class="fw-300"><i>Antrian Farmasi</i></span>
                    <div class="top-button-div">
                        <div class="inner">
                            <button type="button" class="btn btn-sm btn-primary" id="plasma-btn"
                                title="Tampilkan popup plasma">
                                <i class="fas fa-desktop text-light" style="transform: scale(1.8)"></i>
                                &nbsp;
                                Plasma Window
                            </button>
                        </div>
                    </div>
                </h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">

                    <form method="get">
                        @csrf

                        <div class="row justify-content-center">


                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="antrian-a-tab" data-bs-toggle="tab"
                                        data-bs-target="#antrian-a-tab-pane" type="button" role="tab"
                                        aria-controls="antrian-a-tab-pane" aria-selected="true">Umum & Asuransi: Non
                                        Racikan</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="antrian-b-tab" data-bs-toggle="tab"
                                        data-bs-target="#antrian-b-tab-pane" type="button" role="tab"
                                        aria-controls="antrian-b-tab-pane" aria-selected="false">Umum & Asuransi:
                                        Racikan</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="antrian-c-tab" data-bs-toggle="tab"
                                        data-bs-target="#antrian-c-tab-pane" type="button" role="tab"
                                        aria-controls="antrian-c-tab-pane" aria-selected="false">BPJS: Non
                                        Racikan</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="antrian-d-tab" data-bs-toggle="tab"
                                        data-bs-target="#antrian-d-tab-pane" type="button" role="tab"
                                        aria-controls="antrian-d-tab-pane" aria-selected="false">BPJS: Racikan</button>
                                </li>
                            </ul>
                        </div>

                        <div class="row justify-content-center">
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="antrian-a-tab-pane" role="tabpanel"
                                    aria-labelledby="antrian-a-tab" tabindex="0">
                                    @include('pages.simrs.farmasi.antrian-farmasi.partials.table-a')
                                </div>
                                <div class="tab-pane fade" id="antrian-b-tab-pane" role="tabpanel"
                                    aria-labelledby="antrian-b-tab" tabindex="0">
                                    @include('pages.simrs.farmasi.antrian-farmasi.partials.table-b')
                                </div>
                                <div class="tab-pane fade" id="antrian-c-tab-pane" role="tabpanel"
                                    aria-labelledby="antrian-c-tab" tabindex="0">
                                    @include('pages.simrs.farmasi.antrian-farmasi.partials.table-c')
                                </div>
                                <div class="tab-pane fade" id="antrian-d-tab-pane" role="tabpanel"
                                    aria-labelledby="antrian-d-tab" tabindex="0">
                                    @include('pages.simrs.farmasi.antrian-farmasi.partials.table-d')
                                </div>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
