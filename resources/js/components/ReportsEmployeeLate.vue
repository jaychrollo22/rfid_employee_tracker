<template>
    <div>

        <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
            <div class="subheader py-2 py-lg-12 subheader-transparent" id="kt_subheader">
                <div class="container d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                    <div class="d-flex align-items-center flex-wrap mr-1">
                        <div class="d-flex flex-column">
                            <h2 class="text-white font-weight-bold my-2 mr-5">Employee Count per Device</h2>
                            <div class="d-flex align-items-center font-weight-bold my-2">
                                <a href="#" class="opacity-75 hover-opacity-100">
                                    <i class="flaticon2-shelter text-white icon-1x"></i>
                                </a>
                                <span class="label label-dot label-sm bg-white opacity-75 mx-3"></span>
                                <a href="" class="text-white text-hover-white opacity-75 hover-opacity-100">Reports</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex flex-column-fluid">
                <div class="container vehicle-container">
                    <div class="card card-custom gutter-b">
                        <div class="card-header flex-wrap py-3">
                            <div class="card-title">
                                <h3 class="card-label"><i class="fa fa-map-marker-alt icon-xl"></i></h3>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row mt-3">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" style="cursor:pointer">
                                                <i class="fas fa-search text-dark-50"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control" placeholder="Search" @keyup="changeurlquery"
                                            v-model="filterData.search">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <input type="date" class="form-control" @change="changeurlquery" title="From"
                                            v-model="filterData.from">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <input type="date" class="form-control" @change="changeurlquery" title="to"
                                            v-model="filterData.to">
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive mt-2">
                                <table class="table table-checkable" id="kt_datatable">
                                    <thead>
                                        <tr>
                                            <th>ID Number</th>
                                            <th>Name</th>
                                            <th>Department</th>
                                            <th>Company</th>
                                            <th>Location</th>
                                            <th>Arrival Time</th>
                                            <th>Late</th>
                                            <td></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-if="isProcessing">
                                            <td colspan="6">
                                                <div class="col-md-12 pt-5 pb-5">
                                                    <div class="spinner spinner-left spinner-primary spinner-lg">
                                                        <span class="ml-15">Loading.. Please wait..</span>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr v-else v-for="(item, i) in items" :key="i">

                                            <td style="vertical-align: middle;">
                                                <small>{{ item.id_number }}</small>
                                            </td>
                                            <td style="vertical-align: middle;">
                                                <small>{{ item.name }}</small>
                                            </td>
                                            <td style="vertical-align: middle;">
                                                <small>{{ item.department }}</small>
                                            </td>
                                            <td style="vertical-align: middle;">
                                                <small>{{ item.company }}</small>
                                            </td>
                                            <td style="vertical-align: middle;">
                                                <small>{{ item.location }}</small>
                                            </td>
                                            <td style="vertical-align: middle;">
                                                <small v-if="item.employee_current_location_first_logs">
                                                    {{ item.employee_current_location_first_logs.local_time }}
                                                </small>
                                                <small v-else class="text-danger">No Logs Detected</small>
                                            </td>
                                            <td style="vertical-align: middle;">
                                                <small v-if="item.employee_current_location_first_logs">
                                                    {{ timeDifference(item.employee_current_location_first_logs.local_time) }}</small>
                                            </td>
                                            <td style="vertical-align: middle;">
                                                <a :href="'/view-history-logs?id=' + item.employee_id + '&v=' + Math.random()"
                                                    target="_blank"><i class="fas fa-street-view text-warning"
                                                        style="cursor:pointer;" title="View Logs"></i></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <table-pagination v-if="items.length > 0" :pagination="pagination" v-on:updatePage="goToPage"
                                v-on:doChangeLimit="changePageCount" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>

import listFormMixins from '../list-form-mixins.vue';
export default {
    mixins: [listFormMixins],
    data() {
        return {
            endpoint: '/reports-employee-per-late-count',
            doors: [],
        }
    },
    created() {
        this.checkurlquery();
        this.getRfidDoors();
        this.fetchList();
    },
    methods: {
        timeDifference(start_time) {

            let startDateTime = new Date(start_time);

            const year = startDateTime.getFullYear();
            const month = String(startDateTime.getMonth() + 1).padStart(2, '0'); // Months are zero-based
            const day = String(startDateTime.getDate()).padStart(2, '0');

            let endDateTime = new Date(year + '-' + month + '-' + day + ' 08:00:00');

            if (startDateTime > endDateTime) {

                // Compute the difference in milliseconds
                const differenceInMilliseconds = startDateTime - endDateTime;

                // Convert milliseconds to seconds, minutes, and hours
                const seconds = Math.floor(differenceInMilliseconds / 1000);
                const minutes = Math.floor(seconds / 60);
                const hours = Math.floor(minutes / 60);

                // Calculate remaining minutes and seconds
                const remainingMinutes = minutes % 60;
                const remainingSeconds = seconds % 60;

                if (hours > 0) {
                    return hours + ' hours ' + remainingMinutes + ' minutes ' + remainingSeconds + ' seconds';
                } else {
                    return remainingMinutes + ' minutes ' + remainingSeconds + ' seconds';
                }
            }

        },
        getCurrentLocation(current_location) {
            let v = this;
            var location = Object.values(v.doors).filter(door => {
                if (current_location.controller_id == door.controller_id && current_location.door_id == door.door_id) {
                    return door;
                }
            });
            if (location.length > 0) {
                return location[0].door_name;
            }
        },
        locationColor(current_location) {
            let v = this;
            var location = Object.values(v.doors).filter(door => {
                if (current_location.controller_id == door.controller_id && current_location.door_id == door.door_id) {
                    return door;
                }
            });
            if (location.length > 0) {
                if (location[0].rfid_controller) {
                    if (location[0].rfid_controller.location == 'BGC') {
                        return 'text-success';
                    }
                    else if (location[0].rfid_controller.location == 'MANILA') {
                        return 'text-primary';
                    }
                    else if (location[0].rfid_controller.location == 'ILOILO') {
                        return 'text-warning';
                    } else {
                        return 'text-default';
                    }
                } else {
                    return 'text-default';
                }
            }
        },
        getRfidDoors() {
            let v = this;
            v.doors = [];
            axios.get('/get-rfid-settings-doors-data')
                .then(response => {
                    v.doors = response.data;
                })
                .catch(error => {
                    v.errors = error.response.data.error;
                })
        },
        checkurlquery() {
            const queryString = window.location.search;
            const urlParams = new URLSearchParams(queryString);
            this.filterData.search = urlParams.get('search') ? urlParams.get('search') : "";
            this.filterData.from = urlParams.get('from') ? urlParams.get('from') : "";
            this.filterData.to = urlParams.get('to') ? urlParams.get('to') : "";
        },
        changeurlquery() {
            const currentURL = window.location.href;
            const urlSearchParams = new URLSearchParams(currentURL.split('?')[1]);
            urlSearchParams.set('search', this.filterData.search);
            urlSearchParams.set('from', this.filterData.from);
            urlSearchParams.set('to', this.filterData.to);
            const newURL = `${window.location.origin}${window.location.pathname}?${urlSearchParams.toString()}`;
            window.history.replaceState({}, '', newURL);
            this.searchKeyUp();
        },
    },
}
</script>


