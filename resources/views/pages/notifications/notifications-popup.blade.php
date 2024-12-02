<div id="notificationDropdown">
  <div class="notifications_content">
      <div class="d-flex items-center justify-content-between">
        <h6>Njoftimet:</h6>
        <span class="close-notifications cursor-pointer d-flex">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </span>
    </div>
      <ul id="notificationList"></ul>
      <div class="markall">
          <form action="{{ route('notifications.markAllAsRead') }}" method="POST" style="display: inline;">
              @csrf
              @method('PATCH')
              <button class="markallasread">Lexo te gjitha</button>
          </form>
      </div>
      <hr>
      <div class="externalnotifications">
          <a href="/notifications/preferences">Preferencat</a>
          <a href="/notifications" class="archive">Arkiva</a>
      </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    loadNotifications();


    const closeNotification = document.querySelector('.close-notifications');

    if (closeNotification) {
        closeNotification.addEventListener('click', () => {
            notificationDropdown.classList.remove('show');

            setTimeout(() => {
                notificationDropdown.style.display = 'none';
            }, 300);
        });
    }


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
