
const content_container = document.querySelector("#main")
let page_title = document.querySelector(".page-title")
let timer, timerCountdown, timeEnded = false;
let BASE_URI = "https://talesfrom.space/vet/";
// let BASE_URI = "http://localhost/drgame/";

home();
enableSpecialLinks();

function home() {
	fetch_url = BASE_URI + "app/home/"
	ajaxCall(fetch_url)
}


// makes all the buttons we created on the page work with fetch
function enableSpecialLinks() {
	fetch_links = document.querySelectorAll(".btn-fetch")
	fetch_links.forEach(function(link){
		if (!link.classList.contains("submit")) {
			link.addEventListener('click', function(e){
				if (!link.classList.contains("disabled") && link.getAttribute("data-link")) {
					fetch_url = link.getAttribute("data-link")
					ajaxCall(fetch_url)
				}
			})
		} else {
			link.addEventListener('click', function(e){
				if (!link.classList.contains("disabled") && link.getAttribute("data-link")) {
					fetch_url = link.getAttribute("data-link")
					let form_info = getFormInfo();
					ajaxCall(fetch_url, true)
				}
			})
		}
	})

	home_title = document.querySelector(".home .page-title")
	if (home_title) {
		home_title.addEventListener("click", function(){
			location.reload();
		})
	}
}

function startTimerGame() {
	let time_btn = document.querySelector(".btn-js");
	let page_info = [];
	let doneLink = 
	page_info['body_classes'] = "timerGame";
	let second_count = time_btn.getAttribute("data-timer")
	if (Number.isNaN(second_count)) {second_count = 30}

	page_info['body'] = [
		['div', 'timer-container'],
		['p', 'Click the button when done!']
	];
	page_info["links"] = [];
	page_info["links"]["timeFinish"] = true;
	page_info["links"]["jsLink"] = true;
	buildPage(page_info);

	let timer_container = document.querySelector(".timer-container");
	if (timer_container) {
		let display_timer = document.createElement("p")
		let text = document.createTextNode(second_count + "s")

		display_timer.setAttribute("id", "timer")
		display_timer.setAttribute("data-count", parseInt(second_count));
		display_timer.append(text);

		timer_container.append(display_timer)

		timer = setInterval(function(){
			display_timer = document.querySelector("#timer")
			let num = parseInt(display_timer.getAttribute("data-count"))
			if (num <= 0) {
				failTimerGame();
			} else {
				--num
				display_timer.setAttribute("data-count",num)
				display_timer.innerText = num + 's';
				if (num < 5) {
					display_timer.classList.add("danger")
				}
			}
		}, 1000)
	}

}

function finishTimerGame() {
	clearInterval(timer)
	showGameResponse("GOOD JOB", "Your pet is feeling a little better already!", "Next", BASE_URI + "app/game/run/?success=1")
}

function showGameResponse(title, message, link_name, link, failed = false) {
	let block = document.createElement("div")
	block.classList = "block"
	content_container.append(block)

	let container = document.createElement("div")
	container.classList = "game-response-container"

	let title_elem = document.createElement("h3")
	let title_text = document.createTextNode(title)
	title_elem.append(title_text)
	title_elem.classList = "game-response-heading"
	if (failed) title_elem.classList.add("danger")
	container.append(title_elem)

	let message_elem = document.createElement("p")
	let message_text = document.createTextNode(message)
	message_elem.classList = "game-response-message"
	message_elem.append(message_text)
	container.append(message_elem)


	let link_elem = document.createElement('p')
	let link_text = document.createTextNode(link_name)
	link_elem.setAttribute('data-link', link);
	link_elem.classList.add('btn', 'btn-fetch', 'game-response-link');

	link_elem.append(link_text);
	container.append(link_elem)

	content_container.append(container);

	enableSpecialLinks();
}

function failTimerGame() {
	clearInterval(timer)
	let tool = document.querySelector(".page-title").innerText
	showGameResponse("OH NO", "You didn't finish in time, want to try again?", "Try Again", BASE_URI + "app/game/run/?success=0", true)
}



function ajaxCall(fetch_url, body = false, testing = false) {
	console.log("run")
		let type;
		if (body === true) {
			body = new FormData(document.querySelector("form"));
		}
		fetch(fetch_url, {
			method: 'POST',
			headers: {
				'Accept': 'text/html, application/json',
			},
		    redirect: 'follow',
			body: body,
			cors: "same-origin",
			credentials: 'include'
		}).then((response) => {
				if (response.status == 404) {
					console.error("dats no good, code 404")
					data = false;
				} else {
					if (!testing) {
						data = response.json();
					} else {
						data = response.text();
					}

				}
				return data;
			}).then((data) => {
					if (!data) {
						return;
					}
					if (!testing) {
						if (data['error']) {
							buildPage(data, data['error']);
						} else {
							buildPage(data);
						}
					}
				})

}

function buildPage(page_info, error = false) {
	Object.entries(page_info);

	if (!error) {
		clearPage();
		if (page_info['title']) page_title.innerText = page_info['title'].replace("_", " ");
		if (page_info["body_classes"]) {
			document.querySelector("body").classList = page_info["body_classes"];
		} else {
			document.querySelector("body").classList = null;
		}

		if (page_info['treatment_counter']) {
			treatment_counter = document.querySelector(".treatment-counter")
			if (treatment_counter) {
				treatment_counter.innerText = page_info['treatment_counter']
			} else {
				buildTreatmentCounter(page_info['treatment_counter'])
			}
		}

		if (page_info["body"] !== undefined) {
			for (element in page_info["body"]) {
				let new_element = document.createElement(page_info["body"][element][0]);

				switch (page_info["body"][element][0]) {
					case "img": 
						new_element.setAttribute("src", page_info["body"][element][1])
						new_element.setAttribute("alt", page_info["body"][element][2].replace("_", " "))
						break;

					case "a":
						let link_text = document.createTextNode(page_info["body"][element][1]);
						new_element.append(link_text)
						new_element.classList = "btn btn-link";
						new_element.setAttribute("href", page_info["body"][element][2])
						break;

					case "div":
						if (page_info["body"][element][1]) new_element.classList = page_info["body"][element][1];
						break;


					default:
						let element_text = document.createTextNode(page_info["body"][element][1]);
						new_element.append(element_text);
						break;
				}

				content_container.append(new_element);

			}
		} 

		if (page_info["form"] !== undefined) {
			buildForm(page_info['form']);
		} 

		if (page_info["links"]  !== undefined) {
			buildLinks(page_info);
		} 

		if (page_info["celebrate"]) {
			treatment_counter = document.querySelector(".treatment-counter")
			if (treatment_counter) treatment_counter.remove();

			document.body.classList.add("celebrate")
			confetti.start();
			confetti.alpha = 0.8;
			setTimeout(function(){ confetti.stop(); }, 12000);
		} 

	} else {
		if (page_info['error'] = "missingParam") {

			// clear previous errors
			all_inputs = document.querySelectorAll("input")
			all_inputs.forEach(function(input){
				if (input.classList.contains("error")) input.classList.remove("error")
			})

			// tell the user whats wrong
			showError("Please answer all questions!");

			// specify which inputs are incorrect
			for (param in page_info["missingParams"]) {
				if (param != "")
				missingParam = document.querySelector("[name=" +page_info["missingParams"][param].replace(/\s/g,'') + "]")
				let label = document.querySelector("[for=" +page_info["missingParams"][param].replace(/\s/g,'') + "]")
				if (missingParam) {
					missingParam.classList.add("error")
					label.classList.add("error")
				}
			}
		}
	}

	// if (page_info["links"] == undefined && page_info["form"] == undefined) {
	// 	console.error("no way to continue");
	// }
	enableSpecialLinks();
}

function buildTreatmentCounter(treatment_counter_text) {
	container = document.querySelector("header")

	let counter = document.createElement("p")
	let counter_text = document.createTextNode(treatment_counter_text)
	counter.append(counter_text)
	counter.classList = "treatment-counter"

	container.append(counter)
}

function clearPage() {
	if (content_container.children.length > 0) {
		content_container.innerHTML = '';
	}
}

function buildForm(form_info) {
	let form_container = document.createElement("form")
	for (form_item in form_info) {
		if (form_item != 'submit') {
			console.log(form_item)
			let display_name = form_info[form_item]['displayName']
			let type = form_info[form_item]['type']

			let label = document.createElement('label');
			let label_text = document.createTextNode(display_name)
			label.setAttribute('for', form_item);
			label.append(label_text);

			let input = document.createElement('input');
			input.setAttribute('type', type);
			input.setAttribute('name', form_item);
			input.setAttribute('placeholder', display_name);
			input.setAttribute('required', true);

			let input_container = document.createElement('div');
			container_name = form_item + 'container'
			input_container.classList.add(container_name);

			input_container.append(label)
			input_container.append(input)

			form_container.append(input_container)
		} else {
			let btn = document.createElement('p')
			let btn_text = document.createTextNode(form_info[form_item]['text'])
			btn.setAttribute('data-link', form_info[form_item]['link']);
			btn.classList.add('btn', 'btn-fetch', 'submit');

			btn.append(btn_text);
			form_container.append(btn)

		}
	}
	content_container.append(form_container)
}

function buildLinks(page_info) {
	let links = page_info['links'];
	link_container = document.createElement("div")
	if (links['container']) {
		link_container.classList.add(links['container'])
	}

	if (links['timeGame']) {
		let second_count = links['timeGame']
		let btn = document.createElement('p')
		let btn_text = document.createTextNode("Ready!")
		btn.setAttribute('data-timer', second_count);
		btn.setAttribute('onclick', "startTimerGame()");
		btn.classList.add('btn', 'btn-js');
		btn.append(btn_text);
		link_container.append(btn)
	} else if (links['timeFinish']) {
		let btn = document.createElement('p')
		let btn_text = document.createTextNode("Done!")
		btn.setAttribute('onclick', "finishTimerGame()");
		btn.classList.add('btn', 'btn-js');
		btn.append(btn_text);
		link_container.append(btn)
	} else {
		for (link in page_info['links']) {
			if(link != 'container') {
				let this_link = links[link]
				let btn = document.createElement('p')
				let btn_text = document.createTextNode(this_link['text'].replace("_", " "))
				btn.setAttribute('data-link', this_link['link']);
				btn.classList.add('btn', 'btn-fetch', link);

				btn.append(btn_text);
				link_container.append(btn)
			}
		}		
	}

	content_container.append(link_container)

}

function showError(e_msg) {
	let e_container = document.createElement("div")
	let e = document.createElement("p")
	let e_text = document.createTextNode(e_msg)

	let prev_e_container = document.querySelector(".errorContainer")
	if (prev_e_container) prev_e_container.remove();

	e_container.classList.add("errorContainer")
	e.classList.add("error")
	e.append(e_text)
	e_container.append(e)
	content_container.insertBefore(e_container, document.querySelector("form"))

}

function getFormInfo() {
	let form = document.querySelector("form");
	let form_data = new FormData(form)
	return form_data;
}


// disables all those fetch links so they can't spam the server while it's getting a request
function disableLinks() {
	alinks = document.querySelectorAll(".btn-fetch")
	alinks.forEach(function(link){
		link.classList.add("btn-disabled")
	})
}

// re-enables those same links and adds functionality to any notices that cropped up
function enableAjaxLinks() {
	alinks = document.querySelectorAll(".f_link")
	alinks.forEach(function(link){
		if (link.classList.has("btn-disabled")) {
			link.classList.remove("btn-disabled")
		}
	})

}
