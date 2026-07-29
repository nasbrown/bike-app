const initializeMap = (id = 'map', coordinates = ['42.0988', '-75.9206'], num = 20) => {
    return L.map(id, {center: coordinates, zoom: num})
}

const initializeTileLayer = (tileLayer, zoom, credit, theMap) => {
    return L.tileLayer(tileLayer, {
        maxZoom: zoom,
        attribution: credit,
    }).addTo(theMap)
}

const myNewMarker = (coordinates = []) => { 
    return L.marker(coordinates)
}

const addToMap = (func, theMap) => {
    return func.addTo(theMap) 
}

const getUserLocation = (coords = ['42.0988', '-75.9206']) => {

    let coordinates = []

    navigator.geolocation.getCurrentPosition((position) => {
        return coords = [`${position.coords.latitude}`, `${position.coords.longitude}`]
    })

    coordinates = coords

    return coordinates
}
/*
const getActualUserLocation = (coords = ['42.0988', '-75.9206']) => {
    let watchId = null

    const positionOptions = {
        enableHighAccuracy: true,
        timeout: 10000,
        maximumAge: 0
    }

        watchId = navigator.geolocation.watchPosition((newPos) => {
             coords = [`${newPos.coords.latitude}`, `${newPos.coords.longitude}`]

        }, (error) => {
            console.warn(`${error.message}`)
        }, positionOptions)
 

    return coords
}
    */

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
    let dataBlockArr = []

    return {
        coordinatesArray: coords,
        coordPair: coordPairsArr,
        markerArr: markArr,
        dataArr: dataBlockArr
    }
}

const getBikeDataHtml = (arr) => {
    return arr.map((data) => {
        return `
        <div class="data-container">
                    <div class="data-img">
                        <img src="../uploads/${data.image_file}">
                    </div>
                    <div class="data-block">
                        <p>Location: ${data.location_name}</p>
                        <button data-id="user-${data.id}">Go To Location</button>
                    </div>
                    <div class="data-x">
                        <div>
                            <a ping="/bike-app/includes/deleteBikeData.php">
                                <i data-remove="${data.id}" class="fa-solid fa-x"></i>
                            </a>
                        </div>
                    </div>
                </div>
    `
    }).join('')
}

const renderData = (dataHtml) => {
    const userData = document.getElementById('user-data')

    return userData.insertAdjacentHTML('beforeend', dataHtml)
}

const render = (dataHtml) => {
    const userData = document.getElementById('user-data')

    return userData.innerHTML = dataHtml
}

const mapFunctions = await mapMethods()

let map = initializeMap('map', getUserLocation(), 18)

initializeTileLayer('https://{s}.tile-cyclosm.openstreetmap.fr/cyclosm/{z}/{x}/{y}.png', 19, '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>', map)

const fetchMarkerData = async () => {
    try{
        const res = await fetch('/bike-app/includes/markerData.php')

        if(!res.ok){
            throw new Error(`HTTP Status error: ${res.status}`)
        }

      const data = await res.json()

      return data

    } catch(error){
        console.error(`${error}`)
    }
}

const initializeRenderedMarkers = async () => {
    
    let data = await fetchMarkerData()

    const userData = document.getElementById('user-data')

    if(Array.isArray(data)){
         let markers = await data.map((marker) => {
            return addToMap(myNewMarker([marker.coord_lat, marker.coord_lng]).
                            bindPopup(`
                                <div>${marker.location_name}</div>
                                <div class="data-img-pop"><img src="../uploads/${marker.image_file}"></div>
                                <div>Coordinates: [${marker.coord_lat}, ${marker.coord_lng}]</div>
                                `).
                            openPopup(), map)
        })
  
    } else {
        userData.innerHTML = `<h2>Click on the map and save a location on the map!</h2>`
   }
}

const initializePastLocations = async () => {
    let dataLoc = await fetchMarkerData()

    let dataHtml = ''

    const userData = document.getElementById('user-data')

    if(Array.isArray(dataLoc)){
        mapFunctions.dataArr = dataLoc

        dataHtml = getBikeDataHtml(mapFunctions.dataArr)
    }

    renderData(dataHtml)
}

await initializePastLocations()

await initializeRenderedMarkers()

const renderMarker = (coords = [], locName, imgFile, map) => {
    return addToMap(myNewMarker(coords).
                            bindPopup(`
                                <div>${locName}</div>
                                <div><img src="${imgFile}"></div>
                                <div>Coordinates: [${coords[0]}, ${coords[1]}]</div>
                                `).
                            openPopup(), map)
}

const renderDataBlock = (image_file, location_name, image_id, coord_lat, coord_lng, id) => {
    let dataObj = {
        location_name: location_name,
        id: Number(id),
        image_file: image_file,
        image_id: image_id,
        coord_lat: `${coord_lat}`,
        coord_lng: `${coord_lng}`
    }

    mapFunctions.dataArr.push(dataObj)

    let dataHtml = getBikeDataHtml(mapFunctions.dataArr)

    render(dataHtml)
}

map.on('click', (e) =>{

let coordinates = [e.latlng.lat, e.latlng.lng]

let marker = addToMap(myNewMarker(coordinates), map)

marker._icon.classList.add("hue-change")

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

document.addEventListener('click', async (e) => {
    if(e.target.dataset.remove){
        deleteBikeLoc(e.target.dataset.remove)
        await deleteBikeLocFromDB(e.target.dataset.remove)
    }
})

document.addEventListener('submit', async(e) => {
    e.preventDefault()

    if(e.target.id === 'form-save-db'){

        let bikeElem = document.getElementById('form-save-db')

        const bikeLocationFormData = new FormData(bikeElem)

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

            const data = await res.json()

            map.closePopup()

            renderDataBlock(data.image_file, 
                            bikeLocationFormData.get('loc-name'),
                            data.image_id,
                            mapFunctions.coordPair[0][0],
                            mapFunctions.coordPair[0][1], 
                            data.id)

            renderMarker([mapFunctions.coordPair[0][0], mapFunctions.coordPair[0][1]], 
                          bikeLocationFormData.get('loc-name'),
                          data.image_file,
                          map)


        } catch (error) {
            console.error(`${error}, ${JSON.stringify(bikeLocationFormData)}`)
        }
    }
})

const mynewPopup = (coordinates, html = '', theMap= {}) => { //factory functions
    return L.popup().setLatLng(coordinates).setContent(html).openOn(theMap)
}

const insertFormHtml = () => {
    return `<form method="POST" action="/bike-app/bikeData.php" id="form-save-db" enctype="multipart/form-data">
                    <div class="hidden">Missing Fields</div>
                    <div>
                        <label for="loc-name">Location Name:</label>
                    </div>
                    <div>
                        <input type="text" id="loc-name" name="loc-name" required>
                    </div>
                    <div>
                        <label for="image-file">Take your pictures</label>
                    </div>
                    <div>
                        <input type="file" id="image-file" accept="image/*" capture="environment" name="image-file" required>
                    </div>
                    <button id="capture-btn" type="submit">Take Photo</button>
                </form>`
}

const deleteBikeLocById = (arr, bikeId) => {
    return arr.filter((user) => {
        if(user.id === Number(bikeId)){
            return false
        }

        return true
    })
}

const deleteBikeLoc = (bikeId) => {

    mapFunctions.dataArr = deleteBikeLocById(mapFunctions.dataArr, bikeId)

    let dataHtml = getBikeDataHtml(mapFunctions.dataArr)

    render(dataHtml)
} 

const deleteBikeLocFromDB = async (bikeId) => {
    try {
        const res = await fetch(`/bike-app/includes/deleteBikeData.php`, {
            method: "POST",
            headers: {"Content-Type": "application/json"},
            body: JSON.stringify({ id: bikeId })
        })

        if(!res.ok){
            throw new Error(`HTTP Status error: ${res.status}`)
        }

        const data = await res.text()

        map.eachLayer((layer) => {
                let coords = layer.getLatLng()

                console.log(layer)
            
        })

        console.log(data)
    } catch (error) {
        console.error(`An error occured: ${error}`)
    }
}
