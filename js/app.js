const initializeMap = (id = 'map', coordinates = ['42.0988', '-75.9206'], num = 15) => {
    return L.map(id, {center: coordinates, zoom: num})
}

const initializeTileLayer = (tileLayer, zoom, credit, theMap) => {
    return L.tileLayer(tileLayer, {
        maxZoom: zoom,
        attribution: credit
    }).addTo(theMap)
}

const getUserLocation = (coords = ['42.0988', '-75.9206']) => {
    navigator.geolocation.getCurrentPosition((position) => {
        return coords = [`${position.coords.latitude}`, `${position.coords.longitude}`]
    })

    return coords
}

const getLocationPermissionState = async () => {
    try {
        let permission = await navigator.permissions.query({name: "geolocation"})

        let state =  await permission.state

        if(state === 'granted'){
            console.log(getUserLocation())
            return getUserLocation()
        } else if (state === 'denied'){
            return console.log("Permission denied")
        } else {
            //Run function
           console.log(getUserLocation())
        }

    } catch (error){
        return console.error(` Error message: ${error}`)
    }
}

const mapMethods = async () => {

    let coords = await getLocationPermissionState()

    return {
        coordinatesArray: coords
    }
}

const mapFunctions = mapMethods()

/*
Need to take the value from the navigator and place it inside of the map variable and give a backup just in case it's undefined
*/

let map = initializeMap('map', mapFunctions.coordinatesArray, 15)

initializeTileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', 19, '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>', map)

document.addEventListener('click', (e) => {
    if(e.target.id === 'get-loc'){
        navigator.geolocation.getCurrentPosition((position) => {
            mapFunctions.userLat = position.coords.latitude
            mapFunctions.userLong = position.coords.longitude
            return mapFunctions.coordinatesLatLngArr = [mapFunctions.userLat, mapFunctions.userLong]
        })
    }
})

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
    e.preventDefault()
    if(e.target.id === 'form-save-db'){
        let bikeForm = document.getElementById('form-save-db')

        const bikeLocationFormData = new FormData(bikeForm);
        
        try {
            const res = await fetch('/bike-app/bikeData.php', {
                method: "POST",
                body: bikeLocationFormData
            })

            if(!res.ok){
                throw new Error(`HTTP Status error: ${res.status}`)
            }

            const data = await res.json()

            return data

        } catch (error) {
            console.error(error)
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
