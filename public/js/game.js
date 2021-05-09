const MAX_RETRY_COUNT = 3;

const SVG_SIZE_SCALE = 50;
const SVG_X_OFFSET = 0;
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

let islandSelection = false;

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
            `${island.targetBridgeCount} ${island.id}`,
            color
        );
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

        console.log(drawConnection(connection));
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

        return `${topLine}${bottomLine}`;
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

        return `${leftLine}${rightLine}`;
    }
}

function drawLine(x1, y1, x2, y2, c) {
    return `<line x1="${x1*SVG_SIZE_SCALE+SVG_X_OFFSET}" y1="${y1*SVG_SIZE_SCALE*INVERT_Y+SVG_Y_OFFSET}" x2="${x2*SVG_SIZE_SCALE+SVG_X_OFFSET}" y2="${y2*SVG_SIZE_SCALE*INVERT_Y+SVG_Y_OFFSET}" style="stroke:${c};stroke-width:3"></line>`;
}

function drawCircle(x, y, r, c, f, id) {
    return  `<circle onclick="activateIslandSelection(${id});" cx="${x*SVG_SIZE_SCALE+SVG_X_OFFSET}" cy="${y*SVG_SIZE_SCALE*INVERT_Y+SVG_Y_OFFSET}" r="${r*Math.abs(SVG_SIZE_SCALE)}" stroke="${c}" stroke-width="3" fill="${f}"></circle>`;
}

function drawNumber(x, y, t, c) {
    return `<text x="${x*SVG_SIZE_SCALE + SVG_X_OFFSET}" y="${y*SVG_SIZE_SCALE*INVERT_Y+SVG_Y_OFFSET}" dy="${-1*INVERT_Y*10}" dx="-8" class="number" stroke="${c}">${t}</text>`
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
    drawGrid();
    getIslandData(GAME_ID);
    getConnectionData(GAME_ID);
}

$(document).ready(function () {
    updateSvg();
});