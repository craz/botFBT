import {MDCTabBar} from '@material/tab-bar';
import {MDCSwitch} from '@material/switch';
import {MDCRipple} from '@material/ripple';
import {MDCTextField} from '@material/textfield';
import {MDCFormField} from '@material/form-field';
import {MDCCheckbox} from '@material/checkbox';

const checkbox = new MDCCheckbox(document.querySelector('.mdc-checkbox'));
const formField = new MDCFormField(document.querySelector('.mdc-form-field'));
formField.input = checkbox;

const textPost = new MDCTextField(document.querySelector('.text-post-field'));
const channelPost = new MDCTextField(document.querySelector('.channel-post-field'));


const selector = '.mdc-button, .mdc-icon-button, .mdc-text-field';
const ripples = [].map.call(document.querySelectorAll(selector), function (el) {
    return new MDCRipple(el);
});
const switchControl = new MDCSwitch(document.querySelector('.mdc-switch'));
const tabBar = new MDCTabBar(document.querySelector('.mdc-tab-bar'));
const sendButton = document.getElementById('addPost');

[].map.call(tabBar.tabList_, function (el) {
    (document).getElementById(el.id).addEventListener('click', function (e) {
        let index = el.id.split("-")[2];
        let layoutsCollection = (document).getElementsByClassName('mdc-layout-grid');
        for (let i = 0; i < layoutsCollection.length; i++) {
            layoutsCollection[i].style.display = "none";
        }
        if ((document).getElementById("layout-" + index).style.display == "none") {
            (document).getElementById("layout-" + index).style.display = "block";
        }
    });
});

switchControl.root_.addEventListener('change', function (e) {
    if (e.target.checked) {
        let deferred = document.createElement("span");
        deferred.className = 'mdc-typography--headline5 hint';
        deferred.innerText = 'отложеный';
        deferred.style.color = "#cccccc";
        (document).getElementById('title-layout-1').appendChild(deferred);
        (document).getElementById('datetimePost').style.display = 'block';
    } else {
        document.querySelector(".hint").remove();
        (document).getElementById('datetimePost').style.display = 'none';
    }
});

sendButton.addEventListener("click", function (e) {
    let data = getData();
    /**TODO сюда вставить preloader**/
    if (data) {
        ajax(data)
            .then(res => {
                /**TODO обработка ошибок**/
                if(res == 'error'){

                }else if(res =='success'){
                    let successMsg = document.createElement("span");
                    successMsg.className = 'mdc-typography--headline5 success';
                    successMsg.innerText = ' отправлен!';
                    successMsg.style.color = "green";
                    (document).getElementById('titleCardPost').appendChild(successMsg);
                    setTimeout(function () {
                        (document).querySelector(".success").remove();
                    },3000);
                }
            })
            .catch(error => {
                console.log(error);
            });
    }
});

function ajax(data) {
    return new Promise(function (resolve, reject) {
        let xhr = new XMLHttpRequest();
        xhr.open('POST', '/ajax.php', true);
        xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
        xhr.onload = () => {
            if (xhr.status >= 200 && xhr.status < 300) {
                resolve(xhr.response);
            } else {
                reject(xhr.statusText);
            }
        };
        xhr.onerror = () => reject(xhr.statusText);
        xhr.send(data);
    });
}

function getData() {
    let formData = new FormData();
    let text = (document).getElementById("textPost");
    let file = (document).getElementById("picturePost").files[0];
    let where = (document).getElementById("channelPost");
    let deferred = (document).getElementById('basic-switch').checked;
    let fbPost = (document).getElementById('checkbox-fb').checked;
    let tgPost = (document).getElementById('checkbox-tg').checked;
    if (text.value != '' && file != undefined && where.value != '') {
        formData.append('picture', file, file.name);
        formData.append('text', text.value);
        formData.append('channel', where.value);
        formData.append('deferred', deferred);
        formData.append('fbPost', fbPost);
        formData.append('tgPost', tgPost);
        return formData;
    } else {
        alert('заполните поля, сообщение не отправленно');
        return false;
    }
}

/**TODO Добавить обрабочик для ajax загрузки изображения**/
/*(document).getElementById('picturePost').addEventListener('change',function () {

});*/