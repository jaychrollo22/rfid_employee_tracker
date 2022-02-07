<template>
<div>
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">

        <div class="subheader py-2 py-lg-12 subheader-transparent" id="kt_subheader">
            <div class="container d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <div class="d-flex align-items-center flex-wrap mr-1">
                    <div class="d-flex flex-column">
                        <h2 class="text-white font-weight-bold my-2 mr-5">Dashboard</h2>
                        <div class="d-flex align-items-center font-weight-bold my-2">
                            <a href="" class="text-white text-hover-white opacity-75 hover-opacity-100">Employee Tracker Per Locations</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex flex-column-fluid">
            <div class="container">
                <div class="row">

                    <div class="col-xl-4">
                        <div class="card card-custom gutter-b">
                            <div class="card-body">
                                <div class="text-enter">
                                    <center>
                                        <vue-ellipse-progress :progress="100" :legendValue="90" fontSize="2.5rem" color="#003494">
                                            <p slot="legend-caption" style="cursor:pointer;"><strong>Total In Office</strong></p>
                                        </vue-ellipse-progress>
                                    </center>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-8">
                        <div class="card card-custom gutter-b">
                            <div class="card-body">
                                <div class="text-enter">
                                    <div class="row">
                                        <div class="col-sm-4 mt-5">
                                            <center>
                                                <vue-ellipse-progress :progress="60" :legendValue="60" color="#00713A" :size="150">
                                                    <span slot="legend-value"> / {{ 100 }}</span>
                                                    <p slot="legend-caption" style="cursor:pointer;"><strong>BGC</strong></p>
                                                </vue-ellipse-progress>
                                            </center>
                                            
                                        </div>
                                        <div class="col-sm-4 mt-5">
                                            <center>
                                                <vue-ellipse-progress :progress="60" :legendValue="60" color="#E2231A" :size="150">
                                                    <span slot="legend-value"> / {{ 100 }}</span>
                                                    <p slot="legend-caption" style="cursor:pointer;"><strong>MANILA</strong></p>
                                                </vue-ellipse-progress>
                                            </center>
                                        </div>
                                        <div class="col-sm-4 mt-5">
                                            <center>
                                                <vue-ellipse-progress :progress="80" :legendValue="60" color="#FFD840" :size="150">
                                                    <span slot="legend-value"> / {{ 100 }}</span>
                                                    <p slot="legend-caption" style="cursor:pointer;"><strong>ILOILO</strong></p>
                                                </vue-ellipse-progress>
                                            </center>
                                        </div>
                                        <div class="col-sm-4 mt-5">
                                            <center>
                                                <vue-ellipse-progress :progress="80" :legendValue="60" color="#FF8300" :size="150">
                                                    <span slot="legend-value"> / {{ 100 }}</span>
                                                    <p slot="legend-caption" style="cursor:pointer;"><strong>DAVAO</strong></p>
                                                </vue-ellipse-progress>
                                            </center>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>




    </div>
</div>
</template>

<script>
    import VueEllipseProgress from 'vue-ellipse-progress';
    export default {
        data() {
            return {
                key: []
            }
        },
        methods: {
            name() {
                
            }
        },
    }
</script>

<style lang="scss" scoped>

</style>