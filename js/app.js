const initializeMap = (id = 'map', coordinates, num = 15) => {
    return L.map(id, {center: coordinates, zoom: num})
}

const initializeTileLayer = (tileLayer, zoom, credit, theMap) => {
    return L.tileLayer(tileLayer, {
        maxZoom: zoom,
        attribution: credit
    }).addTo(theMap)
}

let map = initializeMap('map', ['42.0988', '-75.9206'], 15)

initializeTileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', 19, '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>', map)

const mapMethods = () => {

    let coordArr = []
    let coordinates = []

    return {
        coordinatesArray: coordArr,
        coordinatesLatLngArr: coordinates,
    }
}

const mapFunctions = mapMethods()


map.on('click', (e) =>{

mapFunctions.coordinatesLatLngArr = [e.latlng.lat, e.latlng.lng]

let marker = myNewMarker(mapFunctions.coordinatesLatLngArr, map)

mynewPopup(mapFunctions.coordinatesLatLngArr, insertFormHtml(), map)

mapFunctions.coordinatesArray.push(marker)

if(mapFunctions.coordinatesArray.length >= 2){
    console.log('Remove')
    map.removeLayer(mapFunctions.coordinatesArray[0])
    mapFunctions.coordinatesArray.shift(mapFunctions.coordinatesArray[0])
    console.log(mapFunctions.coordinatesArray)
    return
} 
})

document.addEventListener('submit', async(e) => {
    if(e.target.id === 'form-save-db'){
        let bikeForm = document.getElementById('form-save-db')

        let bikeLocationFormData = new FormData(bikeForm);
        
        try {
            const res = await fetch('bike-app/bikeData.php', {
                method: "POST",
                body: ''
            })
        } catch (error) {
            
        }
    }
})

const myNewMarker = (coordinates = [], theMap = {}) => { //factory functions
    return L.marker(coordinates).addTo(theMap)
}

const mynewPopup = (coordinates, html = '', theMap= {}) => { //factory functions
    return L.popup().setLatLng(coordinates).setContent(html).openOn(theMap)
}

const insertFormHtml = () => {
    return `<form method="POST" id="form-save-db" enctype="multipart/form-data">
                    <div>
                        <label for="image-file">Take your picture</label>
                    </div>
                    <div>
                        <input type="file" id="image-file" name="image-file">
                    </div>
                    <div>
                        <label for="loc-name">Location Name:</label>
                    </div>
                    <div>
                        <input type="text" id="loc-name" name="loc-name">
                    </div>
                    <button type="submit">Submit</button>
                </form>`
}
