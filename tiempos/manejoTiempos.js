
    let timerInterval;
    let isRunning = false;
    let startTime = 0;
    let elapsedTime = 0;
    let lapStartTime = 0;
    let laps = [];

    const startStopButton = document.getElementById('startStopButton');
    const lapButton = document.getElementById('lapButton');
    const submitButton = document.getElementById('submitButton');
    const lapsInput = document.getElementById('laps');
    const timerDisplay = document.getElementById('timerDisplay');
    const lapDisplay = document.getElementById('lapDisplay');
    const lapList = document.getElementById('lapList');
    const lapsres = document.getElementById('lapsres');

    startStopButton.addEventListener('click', () => {
        if (isRunning) {
            clearInterval(timerInterval);
            startStopButton.textContent = 'Start';
            lapButton.disabled = true;
            submitButton.disabled = false;
        } else {
            startTime = Date.now() - elapsedTime;
            lapStartTime = Date.now(); // Reset lap start time for each new session
            timerInterval = setInterval(updateTime, 100);
            startStopButton.textContent = 'Stop';
            lapButton.disabled = false;
        }
        isRunning = !isRunning;
    });

    lapButton.addEventListener('click', () => {
        const lapTime = formatTime(elapsedTime);
        laps.push(lapTime);
        lapsInput.value = laps.join('-');


        // Reset lap timer to 00:00:000 for next lap
        lapStartTime = Date.now();

        // Display lap time in the list

    });

    function updateTime() {
        elapsedTime = Date.now() - startTime;
        const formattedTime = formatTime(elapsedTime);
        timerDisplay.textContent = formattedTime; // Display total time on the page

        const lapElapsedTime = Date.now() - lapStartTime; // Time since last lap started
        const formattedLapTime = formatTime(lapElapsedTime);
        lapDisplay.textContent = `Lap Time: ${formattedLapTime}`; // Display lap time on the page
    }

    function formatTime(ms) {
        const minutes = Math.floor(ms / 60000);
        const seconds = Math.floor((ms % 60000) / 1000);
        const milliseconds = ms % 1000;
        //constant  = sin milesimas 

        let constant = parseInt((minutes * 60) + (seconds) + (milliseconds / 1000));
        return `${constant}`;
    }

    function padZero(num, length = 2) {
        return num.toString().padStart(length, '0');
    }

    const proceso = document.getElementById('proceso');
    const subproceso = document.getElementById('subproceso');

    function diffprocess() {


        var corte = ['', 'cutting w/2 terminals without saels',
            'cutting w/1 terminals without saels',
            'cutting w/1 terminals with saels',
            'cutting w/2 terminals with saels',
            'cutting without terminals'
        ];

        var terminales = ['',
            'twist wire',
            'terminal apply with machine',
            'terminal apply with handtool',
            'create set for splice',
            'splice set apply with machine',
            'splice set apply with handtool',
            'put seal on wire',
            'set headshrink',
            'burn headshrirnk w/headgun',
            'burn headshrink w/machine',
            'set tin point',
        ];

        var assembly = ['', 'subassembly',
            'assembly',
            'Add Ties',
        ];
        var loom = ['', 'looming',
            'braiding',
            'set slavee for braiding',
            'Tapping',
            'labeling',

        ];

        if (proceso.value == 'Cutting') {
            subproceso.innerHTML = '';
            for (let i = 0; i < corte.length; i++) {
                subproceso.innerHTML += `<option value="${corte[i]}">${corte[i]}</option>`;
            }

        } else if (proceso.value == 'Terminals') {
            subproceso.innerHTML = '';
            for (let i = 0; i < terminales.length; i++) {
                subproceso.innerHTML += `<option value="${terminales[i]}">${terminales[i]}</option>`;
            }
        } else if (proceso.value == 'Assembly') {
            subproceso.innerHTML = '';
            for (let i = 0; i < assembly.length; i++) {
                subproceso.innerHTML += `<option value="${assembly[i]}">${assembly[i]}</option>`;
            }
        } else if (proceso.value == 'Looming') {
            subproceso.innerHTML = '';
            for (let i = 0; i < loom.length; i++) {
                subproceso.innerHTML += `<option value="${loom[i]}">${loom[i]}</option>`;
            }
        }
    }

    function suproceso() {
        if (subproceso.value == 'cutting w/2 terminals without saels' || subproceso.value == 'cutting w/1 terminals without saels' ||
            subproceso.value == 'cutting w/1 terminals with saels' || subproceso.value == 'cutting w/2 terminals with saels' ||
            subproceso.value == 'cutting without terminals' || subproceso.value == 'twist wire') {
            document.getElementById('size-mm').style.display = 'block';
        } else {
            document.getElementById('size-mm').style.display = 'none';
        }
    }
