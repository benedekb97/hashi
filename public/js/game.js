const MAX_RETRY_COUNT = 3;

const SVG_SIZE_SCALE = 50;
const SVG_X_OFFSET = 500;
const SVG_Y_OFFSET = 500;

const GRID_SIZE_X = 15;
const GRID_SIZE_Y = 15;

const SVG_ISLAND_RADIUS = 0.35;
const SVG_BRIDGE_WIDTH = 0.1;
const SVG_POINT_RADIUS = 0.05;
const SVG_NUMBER_SIZE = 0.3;

const SVG_GREEN = 'green';
const SVG_RED = 'red';
const SVG_BLACK = 'black';
const SVG_WHITE = 'white';
const SVG_GREY = 'grey';

const D2R = 0.5;
const INVERT_Y = -1;

let islandData;
let connectionData;

let islandClicked = false;

function getIslandData(gameId) {
    let retryCount = 0;

    $.ajax({
        url: `/api/games/${gameId}/islands/`,
        success: function (data) {
            drawIslands(data);
        },
        error: function (data) {
            if (retryCount++ >= MAX_RETRY_COUNT-1) {
                return;
            }

            setTimeout(function () {
                getIslandData(gameId);
            }, 1000);
        }
    });
}

function getConnectionData(gameId) {
    let retryCount = 0;

    $.ajax({
        url: `/api/games/${gameId}/connections/`,
        success: function (data) {
            drawConnections(data);
        },
        error: function () {
            if (retryCount++ >= MAX_RETRY_COUNT - 1) {
                return;
            }

            setTimeout(function () {
                getConnectionData(gameId);
            }, 1000);
        }
    })
}

function drawIslands(islandData) {
    let svg = $('#game');

    let svgHtml = svg.html();

    islandData.forEach(function(island, index) {
        let color = island.targetBridgeCount === island.bridgeCount ? SVG_GREEN : (
            island.targetBridgeCount < island.bridgeCount ? SVG_RED : SVG_BLACK
        );

        svgHtml += `<g class="island" onclick="setTimeout(function(){clickIsland(${island.id});}, 200);">`;

        svgHtml += drawCircle(
            island.point.horizontalPosition,
            island.point.verticalPosition,
            SVG_ISLAND_RADIUS,
            color,
            SVG_WHITE
        );

        svgHtml += drawNumber(
            island.point.horizontalPosition,
            island.point.verticalPosition,
            `${island.targetBridgeCount}`,
            color
        );

        svgHtml += `</g>`;
    });

    islandData.forEach(function(island, index) {
        let color = island.targetBridgeCount === island.bridgeCount ? SVG_GREEN : (
            island.targetBridgeCount < island.bridgeCount ? SVG_RED : SVG_BLACK
        );
    })

    svg.html(svgHtml);
}

function drawConnections(connectionData) {
    let svg = $('#game');

    let svgHtml = svg.html();

    connectionData.forEach(function(connection, index) {
        svgHtml += drawConnection(connection);
    });

    svg.html(svgHtml);
}

function drawConnection(connection) {
    if (connection.axis.axis === 'horizontal') {

        let firstLarger = connection.first_island.point.horizontal_position > connection.second_island.point.horizontal_position ? 1 : -1;

        let topLine = drawLine(
            (connection.first_island.point.horizontal_position - firstLarger * SVG_ISLAND_RADIUS),
            (connection.first_island.point.vertical_position + D2R * SVG_BRIDGE_WIDTH),
            (connection.second_island.point.horizontal_position + firstLarger * SVG_ISLAND_RADIUS),
            (connection.second_island.point.vertical_position + D2R * SVG_BRIDGE_WIDTH),
            SVG_BLACK
        );

        let bottomLine = drawLine(
            (connection.first_island.point.horizontal_position - firstLarger * SVG_ISLAND_RADIUS),
            (connection.first_island.point.vertical_position - D2R * SVG_BRIDGE_WIDTH),
            (connection.second_island.point.horizontal_position + firstLarger * SVG_ISLAND_RADIUS),
            (connection.second_island.point.vertical_position - D2R * SVG_BRIDGE_WIDTH),
            SVG_BLACK
        );

        let centreLine = drawLine(
            (connection.first_island.point.horizontal_position - firstLarger * SVG_ISLAND_RADIUS),
            (connection.first_island.point.vertical_position),
            (connection.second_island.point.horizontal_position + firstLarger * SVG_ISLAND_RADIUS),
            (connection.second_island.point.vertical_position),
            SVG_BLACK
        )

        return connection.count === 2
            ? `<g onclick="clickConnection(${connection.id});" class="connection">${topLine}${bottomLine}</g>`
            : `<g onclick="clickConnection(${connection.id});" class="connection">${centreLine}</g>`;
    } else {
        let firstLarger = connection.first_island.point.vertical_position > connection.second_island.point.vertical_position ? 1 : -1;

        let leftLine = drawLine(
            (connection.first_island.point.horizontal_position - D2R * SVG_BRIDGE_WIDTH),
            (connection.first_island.point.vertical_position - firstLarger * SVG_ISLAND_RADIUS),
            (connection.second_island.point.horizontal_position - D2R * SVG_BRIDGE_WIDTH),
            (connection.second_island.point.vertical_position + firstLarger * SVG_ISLAND_RADIUS),
            SVG_BLACK
        );

        let rightLine = drawLine(
            (connection.first_island.point.horizontal_position + D2R * SVG_BRIDGE_WIDTH),
            (connection.first_island.point.vertical_position - firstLarger * SVG_ISLAND_RADIUS),
            (connection.second_island.point.horizontal_position + D2R * SVG_BRIDGE_WIDTH),
            (connection.second_island.point.vertical_position + firstLarger * SVG_ISLAND_RADIUS),
            SVG_BLACK
        );

        let centreLine = drawLine(
            (connection.first_island.point.horizontal_position),
            connection.first_island.point.vertical_position - firstLarger * SVG_ISLAND_RADIUS,
            connection.second_island.point.horizontal_position,
            connection.second_island.point.vertical_position + firstLarger * SVG_ISLAND_RADIUS,
            SVG_BLACK
        );

        return connection.count === 2
            ? `<g onclick="clickConnection(${connection.id});" class="connection">${leftLine}${rightLine}</g>`
            : `<g onclick="clickConnection(${connection.id});" class=connection">${centreLine}</g>`;
    }
}

function drawLine(x1, y1, x2, y2, c) {
    return `<line x1="${x1*SVG_SIZE_SCALE+SVG_X_OFFSET}" y1="${y1*SVG_SIZE_SCALE*INVERT_Y+SVG_Y_OFFSET}" x2="${x2*SVG_SIZE_SCALE+SVG_X_OFFSET}" y2="${y2*SVG_SIZE_SCALE*INVERT_Y+SVG_Y_OFFSET}" style="stroke:${c};stroke-width:3"></line>`;
}

function drawCircle(x, y, r, c, f) {
    return  `<circle cx="${x*SVG_SIZE_SCALE+SVG_X_OFFSET}" cy="${y*SVG_SIZE_SCALE*INVERT_Y+SVG_Y_OFFSET}" r="${r*Math.abs(SVG_SIZE_SCALE)}" stroke="${c}" stroke-width="3" fill="${f}"></circle>`;
}

function drawNumber(x, y, t, c) {
    return `<text x="${x*SVG_SIZE_SCALE + SVG_X_OFFSET}" y="${y*SVG_SIZE_SCALE*INVERT_Y+SVG_Y_OFFSET}" dy="${-1*INVERT_Y*10}" dx="-8" class="number island" stroke="${c}">${t}</text>`
}

function drawGrid() {
    let svgHtml = $('#game').html();

    for (let i = 0; i < GRID_SIZE_X; i++) {
        for (let j = 0; j < GRID_SIZE_Y; j++) {
            svgHtml += drawCircle(i, j, SVG_POINT_RADIUS, SVG_GREY, SVG_GREY);
        }
    }

    $('#game').html(svgHtml);
}

function updateSvg() {
    clearSvg();

    drawGrid();
    getIslandData(GAME_ID);
    getConnectionData(GAME_ID);
}

function clearSvg() {
    $('#game').html('');
}

$(document).ready(function () {
    updateSvg();
});

function clickConnection(connectionId) {
    $.ajax({
        url: `/api/games/${GAME_ID}/connections/${connectionId}`,
        method: 'GET',
        success: function (connectionData) {
            let firstIsland = connectionData.first_island.id;
            let secondIsland = connectionData.second_island.id;

            $.ajax({
                url: `/api/games/${GAME_ID}/connections/`,
                method: 'POST',
                dataType: 'json',
                data: {
                    "firstIsland": firstIsland,
                    "secondIsland": secondIsland
                },
                success: function() {
                    updateSvg();
                }
            })
        }
    })
}

function clickIsland(islandId) {
    islandClicked = true;

    $(document).one('click', function (event) {
        islandClickedAction(islandId, event);
    });
}

function connectIsland(islandId, direction, retryCount = 0) {
    $.ajax({
        url: `/api/games/${GAME_ID}/islands/${islandId}`,
        method: 'PATCH',
        data: {
            "direction": direction
        },
        dataType: "json",
        success: function () {
            updateSvg();

            islandClicked = false;
        },
        error: function() {
            islandClicked = false;
        }
    })
}

function islandClickedAction(islandId, event) {
    if (!islandClicked) {
        return;
    }

    let cursorHorizontalPosition = event.pageX;
    let cursorVerticalPosition = event.pageY;

    $.ajax({
        url: `/api/games/${GAME_ID}/islands/${islandId}`,
        method: 'GET',
        success: function (islandData) {
            let direction;

            let islandHorizontalPosition = islandData.point.horizontal_position * SVG_SIZE_SCALE + SVG_X_OFFSET;
            let islandVerticalPosition = islandData.point.vertical_position * SVG_SIZE_SCALE * INVERT_Y + SVG_Y_OFFSET;

            let dx = islandHorizontalPosition - cursorHorizontalPosition;
            let dy = islandVerticalPosition - cursorVerticalPosition;

            if (Math.abs(dx) > Math.abs(dy) && dx > 0) {
                direction = 'left';
            } else if (Math.abs(dx) < Math.abs(dy) && dy > 0) {
                direction = 'up';
            } else if (Math.abs(dx) > Math.abs(dy) && dy <= 0) {
                direction = 'right';
            } else {
                direction = 'down';
            }

            connectIsland(islandId, direction);
        },
        error: function() {
            islandClicked = false;
        }
    });
}