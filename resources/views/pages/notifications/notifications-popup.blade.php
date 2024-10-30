 <div style="position: relative;">
            <button id="notificationButton">
                <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="40" height="40" viewBox="0 0 50 50">
<path d="M 25 0 C 22.800781 0 21 1.800781 21 4 C 21 4.515625 21.101563 5.015625 21.28125 5.46875 C 15.65625 6.929688 12 11.816406 12 18 C 12 25.832031 10.078125 29.398438 8.25 31.40625 C 7.335938 32.410156 6.433594 33.019531 5.65625 33.59375 C 5.265625 33.878906 4.910156 34.164063 4.59375 34.53125 C 4.277344 34.898438 4 35.421875 4 36 C 4 37.375 4.84375 38.542969 6.03125 39.3125 C 7.21875 40.082031 8.777344 40.578125 10.65625 40.96875 C 13.09375 41.472656 16.101563 41.738281 19.40625 41.875 C 19.15625 42.539063 19 43.253906 19 44 C 19 47.300781 21.699219 50 25 50 C 28.300781 50 31 47.300781 31 44 C 31 43.25 30.847656 42.535156 30.59375 41.875 C 33.898438 41.738281 36.90625 41.472656 39.34375 40.96875 C 41.222656 40.578125 42.78125 40.082031 43.96875 39.3125 C 45.15625 38.542969 46 37.375 46 36 C 46 35.421875 45.722656 34.898438 45.40625 34.53125 C 45.089844 34.164063 44.734375 33.878906 44.34375 33.59375 C 43.566406 33.019531 42.664063 32.410156 41.75 31.40625 C 39.921875 29.398438 38 25.832031 38 18 C 38 11.820313 34.335938 6.9375 28.71875 5.46875 C 28.898438 5.015625 29 4.515625 29 4 C 29 1.800781 27.199219 0 25 0 Z M 25 2 C 26.117188 2 27 2.882813 27 4 C 27 5.117188 26.117188 6 25 6 C 23.882813 6 23 5.117188 23 4 C 23 2.882813 23.882813 2 25 2 Z M 27.34375 7.1875 C 32.675781 8.136719 36 12.257813 36 18 C 36 26.167969 38.078125 30.363281 40.25 32.75 C 41.335938 33.941406 42.433594 34.6875 43.15625 35.21875 C 43.515625 35.484375 43.785156 35.707031 43.90625 35.84375 C 44.027344 35.980469 44 35.96875 44 36 C 44 36.625 43.710938 37.082031 42.875 37.625 C 42.039063 38.167969 40.679688 38.671875 38.9375 39.03125 C 35.453125 39.753906 30.492188 40 25 40 C 19.507813 40 14.546875 39.753906 11.0625 39.03125 C 9.320313 38.671875 7.960938 38.167969 7.125 37.625 C 6.289063 37.082031 6 36.625 6 36 C 6 35.96875 5.972656 35.980469 6.09375 35.84375 C 6.214844 35.707031 6.484375 35.484375 6.84375 35.21875 C 7.566406 34.6875 8.664063 33.941406 9.75 32.75 C 11.921875 30.363281 14 26.167969 14 18 C 14 12.261719 17.328125 8.171875 22.65625 7.21875 C 23.320313 7.707031 24.121094 8 25 8 C 25.886719 8 26.679688 7.683594 27.34375 7.1875 Z M 21.5625 41.9375 C 22.683594 41.960938 23.824219 42 25 42 C 26.175781 42 27.316406 41.960938 28.4375 41.9375 C 28.792969 42.539063 29 43.25 29 44 C 29 46.222656 27.222656 48 25 48 C 22.777344 48 21 46.222656 21 44 C 21 43.242188 21.199219 42.539063 21.5625 41.9375 Z"></path>
</svg>
                <span id="notificationBadge">
                    0
                </span>
            </button>
        </div>
        <!-- Notification Dropdown -->
        <div id="notificationDropdown">
          <div class="notifications_content">

            <h6>NOTIFICATIONS:</h6>
                 <ul id="notificationList">
                 </ul>
                 <div class="markall">
                   <form action="{{ route('notifications.markAllAsRead') }}" method="POST" style="display: inline;">
                    @csrf
                    @method('PATCH')
                    <button class="markallasread">Mark as read</button>
                </form>
                 </div>
                 <hr>
                 <a href="/notifications" class="archive">Arkiva</a>
          </div>

         
        </div>

         <script>
    document.addEventListener('DOMContentLoaded', function () {
        
                        loadNotifications();

    
        notificationButton.addEventListener('click', function () {
          
            if (notificationDropdown.classList.contains('show')) {
                
                notificationDropdown.classList.remove('show');

               
                setTimeout(() => {
                    notificationDropdown.style.display = 'none';
                }, 300); 
            } else {
               
                notificationDropdown.style.display = 'block'; 
                setTimeout(() => {
                    notificationDropdown.classList.add('show'); 
                }, 10); 
            }
        });


        
        // Fetch unread notifications from the server
        function loadNotifications() {
            fetch("{{ route('notifications.fetchUnread') }}")
                .then(response => response.json())
                .then(data => {
                    const notifications = data.notifications;
                    const notificationList = document.getElementById('notificationList');
                    const notificationBadge = document.getElementById('notificationBadge');

                    // Clear the current notification list
                    notificationList.innerHTML = '';
                    // Update the notification badge count
                    notificationBadge.textContent = notifications.length;

                     if (notifications.length === 0) {
    const noNotificationsMessage = document.createElement('div'); // Use a div to wrap the SVG and text
    noNotificationsMessage.classList = 'nonotification'; // Use flex to align the items
 

    // Create the SVG element
    const svgIcon = document.createElement('div');
    svgIcon.innerHTML = `
        <svg xmlns="http://www.w3.org/2000/svg" width="53" height="52" viewBox="0 0 53 52" fill="none">
            <g clip-path="url(#clip0_5182_7851)">
                <path d="M50.693 34.9294L40.1682 25.458H12.8336L2.30876 34.9294L0.859131 33.3196L12.001 23.291H40.9997L52.1415 33.3196L50.693 34.9294Z" fill="#D6F0C4"></path>
                <path d="M49.25 51.9994H3.75C1.9576 51.9994 0.5 50.5417 0.5 48.7494V33.041H17.8333V34.1243C17.8333 36.8127 20.02 38.9994 22.7083 38.9994H30.2917C32.98 38.9994 35.1667 36.8127 35.1667 34.1243V33.041H52.5V48.7494C52.5 50.5417 51.0424 51.9994 49.25 51.9994ZM2.66667 35.2077V48.7494C2.66667 49.3469 3.15248 49.8327 3.75 49.8327H49.25C49.8475 49.8327 50.3333 49.3469 50.3333 48.7494V35.2077H37.2506C36.7279 38.5785 33.8063 41.166 30.2917 41.166H22.7083C19.1939 41.166 16.2725 38.5785 15.7494 35.2077H2.66667Z" fill="#D6F0C4"></path>
                <path d="M16.0389 8.62695L19.8306 14.5853L18.0026 15.7485L14.2109 9.79019L16.0389 8.62695Z" fill="#D6F0C4"></path>
                <path d="M37.5028 8.62598L39.3307 9.78904L35.5398 15.7474L33.7118 14.5843L37.5028 8.62598Z" fill="#D6F0C4"></path>
                <path d="M25.4166 7.58301H27.5833V14.6247H25.4166V7.58301Z" fill="#D6F0C4"></path>
            </g>
            <defs>
                <clipPath id="clip0_5182_7851">
                    <rect width="52" height="52" fill="white" transform="translate(0.5)"></rect>
                </clipPath>
            </defs>
        </svg>
    `;

    // Create a paragraph for the message
    const messageText = document.createElement('p');
    messageText.textContent = 'No Unread Notifications';
    messageText.style.marginLeft = '10px'; // Add some space between the SVG and text

    // Append the SVG icon and text to the message container
    noNotificationsMessage.appendChild(svgIcon);
    noNotificationsMessage.appendChild(messageText);

    // Append the message container to the notification list
    notificationList.appendChild(noNotificationsMessage);
}
 else {
                        // Loop through and append each notification message
                        notifications.forEach(notification => {
                            const listItem = document.createElement('li');
                            listItem.innerHTML = `
                                ${notification.data.message}
                                <form onsubmit="markAsRead(event, '${notification.id}')">
                                    @csrf
                                    <button class="noti-btn"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye vue-feather__content"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg></button>
                                </form>
                            `;
                            notificationList.appendChild(listItem);
                        });
                    }
                })
                .catch(error => console.error('Error fetching notifications:', error));
        }

        window.markAsRead = function(event, notificationId) {
            event.preventDefault();

            fetch(`{{ url('/notifications') }}/${notificationId}/mark-as-read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ _method: 'PATCH' })
            })
            .then(response => {
                if (response.ok) {
                    loadNotifications();
                } else {
                    console.error('Failed to mark notification as read');
                }
            })
            .catch(error => console.error('Error marking as read:', error));
        }
    });

    
</script>
