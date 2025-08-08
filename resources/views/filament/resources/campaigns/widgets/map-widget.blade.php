<x-filament-widgets::widget>
    <x-filament::card>
        <div wire:ignore id="map" style="height: 500px;"></div>
    </x-filament::card>

       
        <script src="https://unpkg.com/@googlemaps/markerclusterer/dist/index.min.js"></script>
    
<script>(g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})
    ({key: "{{ $this->mapApiKey }}", v: "weekly"});</script>
    <script src="https://unpkg.com/@googlemaps/markerclusterer/dist/index.min.js"></script>
<script>
      async function initMap() {
        // Request needed libraries.
        const { Map, InfoWindow } = await google.maps.importLibrary("maps");
        const { AdvancedMarkerElement, PinElement } = await google.maps.importLibrary(
          "marker",
        );

        const map = new google.maps.Map(document.getElementById("map"), {
          zoom: 10,
          center: { lat: 44.295096794133414, lng:-78.32068367671977 },
          mapId: "{{ $this->mapId }}",
        });
        const infoWindow = new google.maps.InfoWindow({
          content: "",
          disableAutoPan: true,
        });
        // Create an array of alphabetical characters used to label the markers.
        const labels = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        // Add some markers to the map.
        const markers = locations.map((position, i) => {
          const label = labels[i % labels.length];
          const pinGlyph = new google.maps.marker.PinElement({
          glyph: label,
          glyphColor: "white",
          });
          const marker = new google.maps.marker.AdvancedMarkerElement({
          position,
        //   content: pinGlyph.element,
          title: 'Sign',

  content: (() => {
    const img = document.createElement("img");
    img.src = '/storage/Pin.svg';
    img.style.height = '40px'; // Adjust the size as needed
    img.style.width = '40px'; // Adjust the size as needed
    return img;
  })(),
          });

          // markers can only be keyboard focusable when they have click listeners
          // open info window when marker is clicked
        
        marker.addListener('click', ({ domEvent, latLng }) => {
            const { target } = domEvent;

             const contentString = `
                        <strong>Address:</strong> ${position.address}<br>
                        <strong>Placed by:</strong> ${position.placed_by}<br>
                        <strong>Placed at:</strong> ${position.placed_at}<br>
                        <strong>Recovered by:</strong> ${position.recovered_by}<br>
                        <strong>Recovered at:</strong> ${position.recovered_at}<br>
                        <br>
                        <em>Coords: ${position.lat}, ${position.lng}</em>
                `;

            infoWindow.close();
            infoWindow.setContent(contentString);
            infoWindow.open(marker.map, marker);
        });
          return marker;
        });

        // Add a marker clusterer to manage the markers.
        new markerClusterer.MarkerClusterer({ markers, map });
      }

      const locations = @json($this->signs).map(location => ({
        lat: parseFloat(location.lat),
        lng: parseFloat(location.lng),
        // Assuming your signs data from the server includes an 'address' field.
        address: location.address,
        placed_at: location.placed_at,
        recovered_at: location.recovered_at,
        placed_by: location.placed_by,
        recovered_by: location.recovered_by,
      }));

      console.log(locations);
      initMap();
</script>
</x-filament-widgets::widget>