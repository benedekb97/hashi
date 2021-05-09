let retryCount = 0;
let islandData;

function getIslandData(gameId) {
    $.ajax({
        url: `/api/games/${gameId}/islands/`,
        success: function (data) {
            islandData = data;
        },
        error: function (data) {
            if (retryCount++ >= 4) {
                return;
            }

            setTimeout(function () {
                islandData = getIslandData(gameId);
            }, 1000);
        }
    });
}