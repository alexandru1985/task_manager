<template>
<div>
    <h1 class="custom-h1-title">Clients Map</h1>
    <div v-if="this.$root.checkImportCSV > 0" style="height: 700px; width: 100%;">
        <l-map v-if="showMap" :zoom="zoom" :center="center" :options="mapOptions" style="height: 80%;">
            <l-tile-layer :url="url" :attribution="attribution" />
            <ul>
                <li v-for="(client,i) in clients" :key="i">
                    <l-marker :lat-lng="[client.lat,client.long]">
                        <l-popup>
                            <div>
                                <b>{{ client.name }}</b>
                            </div>
                        </l-popup>
                    </l-marker>
                </li>
            </ul>
        </l-map>
    </div>
    <div v-else><default-message></default-message></div> 
</div>
</template>
<script>
import {
    latLng
} from "leaflet";
import {
    LMap,
    LTileLayer,
    LMarker,
    LPopup,
    LTooltip
} from "vue2-leaflet";

export default {
    name: "Example",
    components: {
        LMap,
        LTileLayer,
        LMarker,
        LPopup,
        LTooltip
    },
    data() {
        return {
            zoom: 5,
            center: latLng(40.808886, -96.707775),
            url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
            attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
            currentZoom: 11.5,
            showParagraph: false,
            mapOptions: {
                zoomSnap: 0.5
            },
            showMap: true,
            clients: '',
        };
    },
    methods: {
    },
    created() {
        axios.get('api/get-clients')
            .then(r => r["data"])
            .then(data => {
                    var arr = [];
                    data.forEach((client, index) => {
                        arr[index] = {
                            lat: client.latitude,
                            long: client.longitude,
                            name: client.name
                        }

                    });
                    this.clients = arr;
                }
            )
    },
    mounted() {
    }
};
</script>