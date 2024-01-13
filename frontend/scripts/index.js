let loginBtn = document.getElementById("login_btn")
let logoutBtn = document.getElementById("logout_btn")

if(loginBtn)
	loginBtn.addEventListener("click", handle_login_btn_click)

if(logoutBtn)
	logoutBtn.addEventListener("click", handle_logout_btn_click)

async function handle_login_btn_click(){
	const { value: formValues } = await Swal.fire({
    	title: "Zadajte prihlasovacie údaje",
        confirmButtonColor: "dodgerblue",
		confirmButtonText: "Prihlásiť sa",
        html: `
          <input id="swal-input1" class="swal2-input" placeholder="Meno" type="text">
          <input id="swal-input2" class="swal2-input" placeholder="Heslo" type="password">
        `,
        focusConfirm: false,
        preConfirm: () => {
			let name = document.getElementById("swal-input1").value
			let pswd = document.getElementById("swal-input2").value
			let errorClass = "swal2-input error_input"
			let normalClass = "swal2-input"
			if(!name || !pswd){
				if(!name){
					document.getElementById("swal-input1").setAttribute("class", errorClass)
				}
				else{
					document.getElementById("swal-input1").setAttribute("class", normalClass)
				}
				if(!pswd){
					document.getElementById("swal-input2").setAttribute("class", errorClass)
				}
				else{
					document.getElementById("swal-input2").setAttribute("class", errorClass)
				}
				
				return false
			}
          	return [ name, pswd ];
        }
    });
	if (formValues) {
    	let name = formValues[0]
		let password = formValues[1]
		let login = true

		let req_body = {
			login,
			name,
			password
		}

		req_body = Object.entries(req_body).map(([key,value]) => `${key}=${value}`).join("&")

        const res = await fetch("../backend/API.php", {
            method: "POST",
            headers: {"Content-type": "application/x-www-form-urlencoded; charset=UTF-8"},
            body: req_body
        })

		const ans = await res.json()

		if(!ans.success){
			Swal.fire({
				icon: "error",
				title: "Neplatné prihlasovacie údaje"
			})
		}
		else{
			location.reload()
		}

    }
}

async function handle_logout_btn_click(){
	let req_body = "logout=true"
	const res = await fetch("../backend/API.php", {
		method: "POST",
		headers: {"Content-type": "application/x-www-form-urlencoded; charset=UTF-8"},
		body: req_body
	})
	location.reload()

}

let client

function startConnect(){
    let clientID = "skusame-iot-weekend-projekt-1"
    let host = "broker.hivemq.com"   
    let port = "8000"
    client = new Paho.MQTT.Client(host,Number(port),clientID);
    client.connect({
        onSuccess: () => {
			console.log("pripojeny")
			client.subscribe("kpi/iot/meridian/register-card")
		},
    });
	client.onMessageArrived = handleMqttMessage
	
}

function sendMqttMessage(topic, payload){
	Message = new Paho.MQTT.Message(payload);
    Message.destinationName = topic;
    client.send(Message)
}

startConnect()

async function handle_registration(card_id){
	const { value: formValues } = await Swal.fire({
    	title: "Zadajte registračné údaje",
        confirmButtonColor: "dodgerblue",
		confirmButtonText: "Zaregistrovať",
		showCancelButton: true,
		cancelButtonText: "Zrušiť",
        html: `
          <input id="swal-input1" class="swal2-input" placeholder="Meno" type="text">
          <input id="swal-input2" class="swal2-input" placeholder="Heslo" type="password">
        `,
        focusConfirm: false,
        preConfirm: () => {
			let name = document.getElementById("swal-input1").value
			let pswd = document.getElementById("swal-input2").value
			let errorClass = "swal2-input error_input"
			let normalClass = "swal2-input"
			if(!name || !pswd){
				if(!name){
					document.getElementById("swal-input1").setAttribute("class", errorClass)
				}
				else{
					document.getElementById("swal-input1").setAttribute("class", normalClass)
				}
				if(!pswd){
					document.getElementById("swal-input2").setAttribute("class", errorClass)
				}
				else{
					document.getElementById("swal-input2").setAttribute("class", errorClass)
				}
				
				return false
			}
          	return [ name, pswd ];
        }
    });
	if(formValues)
		console.log("ahhaha")
		return false
	/* if (formValues) {
    	let name = formValues[0]
		let password = formValues[1]
		let login = true

		let req_body = {
			login,
			name,
			password
		}

		req_body = Object.entries(req_body).map(([key,value]) => `${key}=${value}`).join("&")

        const res = await fetch("../backend/API.php", {
            method: "POST",
            headers: {"Content-type": "application/x-www-form-urlencoded; charset=UTF-8"},
            body: req_body
        })

		const ans = await res.json()

		if(!ans.success){
			Swal.fire({
				icon: "error",
				title: "Neplatné prihlasovacie údaje"
			})
		}
		else{
			location.reload()
		}

    } */
}

async function handleMqttMessage(message){
	let card_id = message.payloadString
	Swal.fire({
		icon: "question",
		title: "Prajete si zaregistrovať novú kartu?",
		showCancelButton: true,
		confirmButtonText: "Áno",
		cancelButtonText: "Nie",
		confirmButtonColor: "dodgerblue"
	}).then(async (res) => {
		if(res.isConfirmed){
			let reg_succ = await handle_registration(card_id)
			console.log(reg_succ)
			if(reg_succ)
				sendMqttMessage("kpi/iot/meridian/register-card-result", "success")
			else
				sendMqttMessage("kpi/iot/meridian/register-card-result", "not-successful")
		}
		else{
			sendMqttMessage("kpi/iot/meridian/register-card-result", "not-successful")
		}
	})
}

