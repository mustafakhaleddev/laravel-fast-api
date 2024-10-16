<?php

namespace MKD\FastAPI\Enums;

enum FastApiMethod
{

    case GET;
    case POST;
    case PUT;
    case PATCH;
    case OPTION;
    case DELETE;
    case ANY;
    case REDIRECT;
    case MATCH;

}
