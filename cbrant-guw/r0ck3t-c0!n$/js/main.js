let leftPressed = false;
let leftHeld = false;
let leftReleased = false;
let rightPressed = false;
let rightHeld = false;
let rightReleased = false;
let upPressed = false;
let upHeld = false;
let upReleased = false;
let escPressed = false;
let escHeld = false; 
let escReleased = false;
let enterPressed = false;
let enterHeld = false; 
let enterReleased = false;
let coins = 0;
let gameMode = "start";

function createPlayer(x, y) {
    let player = document.createElement("DIV");
    player.id = "player-1";
    player.classList.add("object");
    player.style.left = x;
    player.style.top = y;
    player.setAttribute("rotation", "0");
    createParts(player, 6);
    document.querySelector(".room").append(player);
}

function createEnemy(x, y) {
    let enemy = document.createElement("DIV");
    enemy.classList.add("object");
    enemy.classList.add("ghost");
    enemy.style.left = x;
    enemy.style.top = y;
    createParts(enemy, 10);
    document.querySelector(".room").append(enemy);
}

function createCoin(x, y) {
    let coin = document.createElement("DIV");
    coin.classList.add("object");
    coin.classList.add("coin");
    coin.style.left = x;
    coin.style.top = y;
    createParts(coin, 4);
    document.querySelector(".room").append(coin);
}

function createParts(object, numberParts) {
    for (var i=0; i<numberParts; i++) {
        let part = document.createElement("DIV");
        part.classList.add("part");
        object.append(part);
    }
}

function startGame() {
    changeHud("game");
    createPlayer((window.innerWidth/2) + "px", (window.innerHeight/2) + "px");
}

function endGame() {
    document.getElementById("player-1").remove();
    coins = 0;
    changeHud("start");
}

function changeHud(hud) {
    gameMode = hud;
    if (hud == "game") {
        let startHud = document.querySelector(".start-screen-wrap");
        if (startHud != null && startHud != undefined) startHud.remove();
        let hudLayer = document.querySelector(".hud");
        hudLayer.id = "game-screen";

        let newHud = document.createElement("DIV");
        newHud.classList.add("game-screen-wrap");

        let coinCounterLabel = document.createElement("H1");
        coinCounterLabel.id = "coin-counter-label";
        coinCounterLabel.classList.add("text", "rainbow-horizontal");
        coinCounterLabel.innerHTML = "CO!N$";

        let coinCounter = document.createElement("H1");
        coinCounter.id = "coin-counter";
        coinCounter.classList.add("text");
        coinCounter.innerHTML = coins;

        let quitLabel = document.createElement("H1");
        quitLabel.id = "quit-label";
        quitLabel.classList.add("text");
        quitLabel.innerHTML = "ESC to Quit";

        newHud.append(coinCounterLabel, coinCounter, quitLabel);
        hudLayer.append(newHud);
    } else if (hud == "start") {
        let gameHud = document.querySelector(".game-screen-wrap");
        if (gameHud != null && gameHud != undefined) gameHud.remove();
        let hudLayer = document.querySelector(".hud");
        hudLayer.id = "start-screen";

        let newHud = document.createElement("DIV");
        newHud.classList.add("start-screen-wrap");

        let title = document.createElement("H1");
        title.id = "title";
        title.classList.add("text", "rainbow-horizontal");
        title.innerHTML = "R0CK3T CO!N$";

        let subTitle = document.createElement("P");
        subTitle.id = "sub-title";
        subTitle.classList.add("text");
        subTitle.innerHTML = "A game made entirely from vanilla JavaScript for logic, HTML Elements for objects &amp; CSS for graphics and animations.";

        let subTitle2 = document.createElement("P");
        subTitle2.id = "sub-title-2";
        subTitle2.classList.add("text");
        subTitle2.innerHTML = "No HTML canvas, no jQuery, no image assets, no JavaScript classes.";

        let pressStart = document.createElement("P");
        pressStart.id = "press-start";
        pressStart.classList.add("text", "float-vertical");
        pressStart.setAttribute("animation", "pulse");
        pressStart.innerHTML = "Press Enter to Play!";

        newHud.append(title, subTitle, subTitle2, pressStart);
        hudLayer.append(newHud);

        document.querySelector("#press-start").addEventListener('click', startGame);
    }
}

function setKeyDown(event) {
    if (event.key.includes("ArrowUp")) {
        if (!upHeld) upPressed = true;
        upHeld = true;
        upReleased = false;
    }
    
    if (event.key.includes("ArrowLeft")) {
        if (!leftHeld) leftPressed = true;
        leftHeld = true;
        leftReleased = false;
    }
    
    if (event.key.includes("ArrowRight")) {
        if (!rightHeld) rightPressed = true;
        rightHeld = true;
        rightReleased = false;
    } 
    
    if (event.key.includes("Escape")) {
        if (!escHeld) escPressed = true;
        escHeld = true;
        escReleased = false;
    } 
    
    if (event.key.includes("Enter")) {
        if (!enterHeld) enterPressed = true;
        enterHeld = true;
        enterReleased = false;
    } 
}

function resetControlStates() {
    if (upPressed && upHeld) {
        upPressed = false;
    }

    if (leftPressed && leftHeld) {
        upPressed = false;
    }

    if (rightPressed && rightHeld) {
        upPressed = false;
    }

    if (escPressed && escHeld) {
        upPressed = false;
    }

    if (enterPressed && enterHeld) {
        enterPressed = false;
    }
}

function setKeyUp(event) {
    if (event.key.includes("ArrowUp")) {
        upReleased = true;
        upHeld = false;
        upPressed = false;
    }
    
    if (event.key.includes("ArrowLeft")) {
        leftHeld = false;
        leftReleased = true;
        leftPressed = false;
    }
    
    if (event.key.includes("ArrowRight")) {
        rightHeld = false;
        rightReleased = true;
        rightPressed = false;
    } 
    
    if (event.key.includes("Escape")) {
        escHeld = false;
        escReleased = true;
        escPressed = false;
    } 
    
    if (event.key.includes("Enter")) {
        enterHeld = false;
        enterReleased = true;
        enterPressed = false;
    } 
}

function controlsReset() {
    leftPressed = false;
    leftHeld = false;
    leftReleased = false;
    rightPressed = false;
    rightHeld = false;
    rightReleased = false;
    upPressed = false;
    upHeld = false;
    upReleased = false;
    escPressed = false;
    escHeld = false; 
    escReleased = false;
}

function defineControls() {
    document.addEventListener("keydown", setKeyDown);
    document.addEventListener("keyup", setKeyUp);
}

function rotateObject(object, interval) {
    if (object !== undefined && object !== null) {
        var rotation = object.getAttribute("rotation");
        if (rotation !== null && rotation !== undefined) {
            rotation = parseFloat(rotation) + interval;
            object.setAttribute("rotation", (rotation%360));
            object.style.transform = "rotate(" + (rotation%360) + "deg)";
        }
    } 
}

function playerMoveForward() {
    let player1 = document.getElementById("player-1");
    if (player1 != undefined && player1 != null) {
        let newX = parseFloat(player1.style.left.replace(/[^\d.-]/g, ""));
        let newY = parseFloat(player1.style.top.replace(/[^\d.-]/g, ""));
        
        newX = newX + (5 * Math.cos(Math.PI * 2 * (parseFloat(player1.getAttribute("rotation"))+270) / 360));
        newY = newY + (5 * Math.sin(Math.PI * 2 * (parseFloat(player1.getAttribute("rotation"))+270) / 360));
        
        player1.style.left = newX + "px";
        player1.style.top = newY + "px";
    }
}

document.addEventListener("DOMContentLoaded", () => {
    changeHud("start");
    setInterval(gameLogic, 16.666);
    defineControls();
});

function gameLogic() {
    if (gameMode == "game") {
        // get objects
        let player1 = document.getElementById("player-1");
        let coin = document.querySelector(".coin.object");

        // control ship
        if (rightHeld) {
            rotateObject(player1, 3.6);
        }

        if (leftHeld) {
            rotateObject(player1, -3.6);
        }

        if (escPressed) {
            endGame();
        }

        if (upHeld) {
            playerMoveForward();
        }

        // create ghost enemy
        if (Math.floor((Math.random() * 600)) == 0) {
            createEnemy((window.innerWidth/4) + "px", (window.innerHeight/2) + "px");
        }

        // ensure a coin always exists
        if (coin == undefined || coin == null) {
            createCoin(((window.innerWidth * Math.floor(Math.random() * 100))/100) + "px", ((window.innerHeight * Math.floor(Math.random() * 100))/100) + "px");
        }

        resetControlStates();
    } else if (gameMode == "start") {
        if (enterPressed) {
            startGame();
        }
    }
}