// Get current timezone info
const nowTZ = Intl.DateTimeFormat().resolvedOptions().timeZone;

// Calculate Current timezone
const now = new Date();
const nowMillis = now.getTime();
const nowOffsetMinutes = -now.getTimezoneOffset(); // getTimezoneOffset returns negative values for positive offsets
const nowOffsetHours = nowOffsetMinutes / 60;
const nowOffsetMillis = nowOffsetMinutes * 60 * 1000;

const nowDateTimeID = now.toLocaleString('id-ID', {
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit',
    timeZoneName: 'short'
});

const utc = new Date(
    now.getUTCFullYear(),
    now.getUTCMonth(),
    now.getUTCDate(),
    now.getUTCHours(),
    now.getUTCMinutes(),
    now.getUTCSeconds(),
    now.getUTCMilliseconds()
);
const utcMillis = utc.getTime();

const itcDateTimeID = utc.toLocaleString('id-ID', {
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit',
    timeZoneName: 'short'
});


console.log(`Current Timezone: ${nowTZ}`);
console.log(`Current ID Datetime: ${nowDateTimeID}`);
console.log(`Current UTC Datetime : ${itcDateTimeID}`);
console.log('=======================================');
console.log(`currentMillis : ${nowMillis}`);
console.log(`currentOffsetHours : ${nowOffsetHours}`);
console.log(`currentOffsetMinutes : ${nowOffsetMinutes}`);
console.log(`currentOffsetMillis : ${nowOffsetMillis}`);
console.log('=======================================');
console.log(`utcMillis : ${utcMillis}`);
console.log('=======================================');
console.log(`utcMillis : ${customMillis}`);
console.log(`utcMillisToDateTime : ${customMillisDatetime}`);
