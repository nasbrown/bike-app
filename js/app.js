const initializeMap = (id = 'map', coordinates = ['42.0988', '-75.9206'], num = 15) => {
    return L.map(id, {center: coordinates, zoom: num})
}

const initializeTileLayer = (tileLayer, zoom, credit, theMap) => {
    return L.tileLayer(tileLayer, {
        maxZoom: zoom,
        attribution: credit,
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
           
            return getUserLocation()
        } else if (state === 'denied'){

            let body = document.body

            return body.innerHTML = `
                <h1>Sorry!, Need Location to continue... Unlock permission here: <button onclick=${getUserLocation()}>Get My Location</button></h1>
            `
        } else if (state === 'prompt') {
          
           return getUserLocation()
        }

    } catch (error){
        return console.error(` Error message: ${error}`)
    }
}

const mapMethods = async () => {

    let coords = await getLocationPermissionState()
    let coordPairsArr = []
    let markArr = []

    return {
        coordinatesArray: coords,
        coordPair: coordPairsArr,
        markerArr: markArr
    }
}

const mapFunctions = await mapMethods()

/*
Need to take the value from the navigator and place it inside of the map variable and give a backup just in case it's undefined
*/

let map = initializeMap('map', mapFunctions.coordinatesArray, 15)

initializeTileLayer('https://{s}.tile-cyclosm.openstreetmap.fr/cyclosm/{z}/{x}/{y}.png', 19, '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>', map)

map.on('click', (e) =>{

let coordinates = [e.latlng.lat, e.latlng.lng]

let marker = addToMap(myNewMarker(coordinates), map)

mynewPopup(coordinates, insertFormHtml(), map)

mapFunctions.coordPair.push(coordinates)
mapFunctions.markerArr.push(marker)

if(mapFunctions.markerArr.length >= 2){
    map.removeLayer(mapFunctions.markerArr[0])
    mapFunctions.markerArr.shift(mapFunctions.markerArr[0])
    mapFunctions.coordPair.shift(mapFunctions.coordPair[0])
    return
}
})

document.addEventListener('submit', async(e) => {
    e.preventDefault()

    if(e.target.id === 'form-save-db'){

        let bikeElem = document.getElementById('form-save-db')

        const bikeLocationFormData = new FormData(bikeElem);

        bikeLocationFormData.append('coordinatesLat', `${mapFunctions.coordPair[0][0]}`)

        bikeLocationFormData.append('coordinatesLng', `${mapFunctions.coordPair[0][1]}`)
        
        try {
            const res = await fetch('/bike-app/includes/bikeData.php', {
                method: "POST",
                body: bikeLocationFormData
            })

            if(!res.ok){
                throw new Error(`HTTP Status error: ${res.status}`)
            }

            const data = await res.text()

            console.log(data)

            map.closePopup()

            //return data

        } catch (error) {
            console.error(`${error}, ${JSON.stringify(bikeLocationFormData)}`)
        }
    }
})

const myNewMarker = (coordinates = []) => { //factory functions
    return L.marker(coordinates)
}

const addToMap = (func, theMap) => {
    return func.addTo(theMap) 
}

const mynewPopup = (coordinates, html = '', theMap= {}) => { //factory functions
    return L.popup().setLatLng(coordinates).setContent(html).openOn(theMap)
}

const insertFormHtml = () => {
    return `<form method="POST" action="/bike-app/bikeData.php" id="form-save-db" enctype="multipart/form-data">
                    <div>
                        <label for="loc-name">Location Name:</label>
                    </div>
                    <div>
                        <input type="text" id="loc-name" name="loc-name">
                    </div>
                    <div>
                        <label for="image-file">Take your pictures</label>
                    </div>
                    <div>
                        <input type="file" id="image-file" name="image-file">
                    </div>
                    <button type="submit">Submit</button>
                </form>`
}
