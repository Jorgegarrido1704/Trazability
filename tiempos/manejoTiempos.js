
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
            'Tapping Body/Assembly',
            'Tapping breakout/Assembly',
            'Plugging/Sub-Assembly',
            'Plugging/Assembly',
            

        ];
        var loom = ['', 'looming',
            'braiding',
            'set slavee for braiding',
            'Tapping/Looming',
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
        let processNumber = document.getElementById('Process_Number');
        let asset = document.getElementById('DescriptionProcess');
        if (subproceso.value == 'cutting w/2 terminals without saels' || subproceso.value == 'cutting w/1 terminals without saels' ||
            subproceso.value == 'cutting w/1 terminals with saels' || subproceso.value == 'cutting w/2 terminals with saels' ||
            subproceso.value == 'cutting without terminals' || subproceso.value == 'twist wire') {
            document.getElementById('size-mm').style.display = 'block';
        }else{
            document.getElementById('size-mm').style.display = 'none';
        }
        if(subproceso.value =='cutting w/2 terminals without saels'){
           document.getElementById('Process_Number').value = '10041';
           document.getElementById('DescriptionProcess').value = 'FB045';
        }if(subproceso.value =='cutting w/1 terminals without saels'){
            document.getElementById('Process_Number').value = '10021';
            document.getElementById('DescriptionProcess').value = 'FB048';
         }if(subproceso.value =='cutting w/1 terminals with saels'){
            document.getElementById('Process_Number').value = '10051';
            document.getElementById('DescriptionProcess').value = 'FB040';
         }if(subproceso.value =='cutting w/2 terminals with saels'){
            document.getElementById('Process_Number').value = '10052';
            document.getElementById('DescriptionProcess').value = 'FB040';
         }if(subproceso.value =='cutting without terminals'){
            document.getElementById('Process_Number').value = '10001';
            document.getElementById('DescriptionProcess').value = 'FB036';
         }if(subproceso.value =='twist wire'){
            document.getElementById('Process_Number').value = '10061';
            document.getElementById('DescriptionProcess').value = 'Pend';
         }if(subproceso.value =='terminal apply with machine'){
            document.getElementById('Process_Number').value = '10081';
            document.getElementById('DescriptionProcess').value = 'FB-081';
         }if(subproceso.value =='terminal apply with handtool'){
            document.getElementById('Process_Number').value = '10101';
            document.getElementById('DescriptionProcess').value = 'Pend';
         }if(subproceso.value =='terminal apply with cannon machine'){
            document.getElementById('Process_Number').value = '10095';
            document.getElementById('DescriptionProcess').value = 'Pend';
         }if(subproceso.value =='create set for splice'){
            document.getElementById('Process_Number').value = '10341';
            document.getElementById('DescriptionProcess').value = 'Pend';
         }if(subproceso.value =='splice set apply with machine'){
            document.getElementById('Process_Number').value = '10301';
            document.getElementById('DescriptionProcess').value = 'FB110';
         }if(subproceso.value =='splice set apply with handtool'){
            document.getElementById('Process_Number').value = '10321';
            document.getElementById('DescriptionProcess').value = 'Pend';
         }if(subproceso.value =='put seal on wire'){
            document.getElementById('Process_Number').value = '10381';
            document.getElementById('DescriptionProcess').value = 'Pend';   

         }if(subproceso.value =='set headshrink'){
            document.getElementById('Process_Number').value = '10361';
            document.getElementById('DescriptionProcess').value = 'Pend';
         }if(subproceso.value =='burn headshrink w/headgun'){
            document.getElementById('Process_Number').value = '10401';
            document.getElementById('DescriptionProcess').value = 'Pend';
         }if(subproceso.value =='burn headshrink w/machine'){
            document.getElementById('Process_Number').value = '10421';
            document.getElementById('DescriptionProcess').value = 'FB-078';
         }if(subproceso.value =='set tin point'){
            document.getElementById('Process_Number').value = '10431';
            document.getElementById('DescriptionProcess').value = 'Pend';
         }if(subproceso.value =='subassembly'){
            document.getElementById('Process_Number').value = '10441';
            document.getElementById('DescriptionProcess').value = 'Pend';
         }if(subproceso.value =='assembly'){
            document.getElementById('Process_Number').value = '10501';
            document.getElementById('DescriptionProcess').value = 'Pend';
         
         }if(subproceso.value =='looming'){
            document.getElementById('Process_Number').value = '10601';
            document.getElementById('DescriptionProcess').value = 'Pend';
         }if(subproceso.value =='labeling'){
            document.getElementById('Process_Number').value = '10701';
            document.getElementById('DescriptionProcess').value = 'Pend';
        }if(subproceso.value =='Add Ties'){
            document.getElementById('Process_Number').value = '10801';
            document.getElementById('DescriptionProcess').value = 'Pend';
         }if(subproceso.value =='Tapping Body/Assembly'){
            document.getElementById('Process_Number').value = '10901';
            document.getElementById('DescriptionProcess').value = 'Pend';
         }
         if(subproceso.value =='Tapping breakout/Assembly'){
            document.getElementById('Process_Number').value = '10902';
            document.getElementById('DescriptionProcess').value = 'Pend';
         }if(subproceso.value =='Plugging/Sub-Assembly'){
            document.getElementById('Process_Number').value = '10951';
            document.getElementById('DescriptionProcess').value = 'Pend';
         }if(subproceso.value =='Plugging/Assembly'){
            document.getElementById('Process_Number').value = '10975';
            document.getElementById('DescriptionProcess').value = 'Pend';
         }if(subproceso.value =='Tapping/Looming'){
            document.getElementById('Process_Number').value = '11001';
            document.getElementById('DescriptionProcess').value = 'Pend';
         }if(subproceso.value == 'braiding'){
            document.getElementById('Process_Number').value = '11101';
            document.getElementById('DescriptionProcess').value = 'Pend';
         }if(subproceso.value == 'set slavee for braiding'){
            document.getElementById('Process_Number').value = '11201';
            document.getElementById('DescriptionProcess').value = 'Pend';
         }


        }