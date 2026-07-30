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
    let mdArr = []

    return {
        coordinatesArray: coords,
        coordPair: coordPairsArr,
        markerDataArr: mdArr,
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

let map = initializeMap('map', getUserLocation(), 10)

initializeTileLayer('https://{s}.tile-cyclosm.openstreetmap.fr/cyclosm/{z}/{x}/{y}.png', 19, '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>', map)

const fetchMarkerData = async () => {
    try{
        const res = await fetch('/bike-app/includes/allMarkerData.php')

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

        await data.map((marker) => {
            let markerData = myNewMarker([marker.coord_lat, marker.coord_lng])

            mapFunctions.markerDataArr.push(addToMap(markerData.
                            bindPopup(`
                                <div>${marker.location_name}</div>
                                <div class="data-img-pop"><img src="../uploads/${marker.image_file}"></div>
                                <div>Coordinates: [${marker.coord_lat}, ${marker.coord_lng}]</div>
                                `), map))

            return addToMap(markerData.
                            bindPopup(`
                                <div>${marker.location_name}</div>
                                <div class="data-img-pop"><img src="../uploads/${marker.image_file}"></div>
                                <div>Coordinates: [${marker.coord_lat}, ${marker.coord_lng}]</div>
                                `), map)
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
    let markerData = myNewMarker(coords)

    mapFunctions.markerDataArr.push(addToMap(markerData.
                            bindPopup(`
                                <div>${locName}</div>
                                <div><img src="../uploads/${imgFile}"></div>
                                <div>Coordinates: [${coords[0]}, ${coords[1]}]</div>
                                `), map))

    return addToMap(markerData.
                            bindPopup(`
                                <div>${locName}</div>
                                <div><img src="../uploads/${imgFile}"></div>
                                <div>Coordinates: [${coords[0]}, ${coords[1]}]</div>
                                `), map)
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

const mynewPopup = (coordinates, html = '', theMap= {}) => { //factory functions
    return L.popup().setLatLng(coordinates).setContent(html).openOn(theMap)
}