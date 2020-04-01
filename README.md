# Overview

This project was created to compare the payload size and speed of serialization formats (Protocol Buffers, JSON, YAML, native PHP object serialization).

# Setup

1. start containers through docker compose:

    `docker-compose up --build`
    
1. enter the `main`container

    `docker-compose exec main sh`    

1. install composer dependencies within `main` container:
    
    `composer install`
    
1. generate code from proto definitions

    `rm -rf src/generated/* && protoc --php_out=src/generated --proto_path=/app/ person.proto`
        
# Execute benchmarks

All benchmarks are executed from the command line - go into container:

     docker-compose exec main sh   

## Decoding benchmarks

    php vendor/bin/phpbench run src/ProtobufBenchmarks/DecodeBench.php --report=aggregate
    
## Encoding benchmarks

    php vendor/bin/phpbench run src/ProtobufBenchmarks/EncodeBench.php --report=aggregate
    
## Payload size benchmarks

    php size_benchmarks.php

# Current results

## Payload

| Technology | PayloadSize (bits) |
|------------|--------------------|
| ProtoBuf   | 672                |
| JSON       | 1504               |
| YAML       | 1552               |
| PHP        | 2648               |

# Speed

| Technology | Encode (mean μs)  | Decode (mean μs)  |
| ---------- | ----------------- | ----------------- |
| ProtoBuf   | 3.385             | 5.569             |
| JSON       | 1.776             | 3.620             |
| YAML       | 62.632            | 118.442           |
| PHP        | 2.208             | 2.643             |


```
+-------------+----------------+-----+-------+-----+------------+----------+----------+----------+----------+---------+--------+--------+
| benchmark   | subject        | set | revs  | its | mem_peak   | best     | mean     | mode     | worst    | stdev   | rstdev | diff   |
+-------------+----------------+-----+-------+-----+------------+----------+----------+----------+----------+---------+--------+--------+
| EncodeBench | encodeProtobuf | 0   | 10000 | 5   | 953,280b   | 3.111μs  | 3.385μs  | 3.243μs  | 3.977μs  | 0.304μs | 8.97%  | 1.91x  |
| EncodeBench | encodeJson     | 0   | 10000 | 5   | 953,280b   | 1.417μs  | 1.776μs  | 1.931μs  | 2.069μs  | 0.252μs | 14.17% | 1.00x  |
| EncodeBench | encodeYaml     | 0   | 10000 | 5   | 1,585,640b | 61.690μs | 62.632μs | 63.117μs | 63.468μs | 0.730μs | 1.17%  | 35.26x |
| EncodeBench | encodePhp      | 0   | 10000 | 5   | 953,280b   | 2.208μs  | 2.474μs  | 2.353μs  | 2.811μs  | 0.234μs | 9.46%  | 1.39x  |
+-------------+----------------+-----+-------+-----+------------+----------+----------+----------+----------+---------+--------+--------+

+-------------+----------------+-----+-------+-----+------------+-----------+-----------+-----------+-----------+---------+--------+--------+
| benchmark   | subject        | set | revs  | its | mem_peak   | best      | mean      | mode      | worst     | stdev   | rstdev | diff   |
+-------------+----------------+-----+-------+-----+------------+-----------+-----------+-----------+-----------+---------+--------+--------+
| DecodeBench | decodeProtobuf | 0   | 10000 | 5   | 1,589,064b | 4.589μs   | 5.569μs   | 5.239μs   | 7.144μs   | 0.846μs | 15.19% | 1.90x  |
| DecodeBench | decodeJson     | 0   | 10000 | 5   | 1,589,064b | 3.152μs   | 3.620μs   | 3.388μs   | 4.536μs   | 0.501μs | 13.83% | 1.23x  |
| DecodeBench | decodeYaml     | 0   | 10000 | 5   | 1,589,064b | 114.876μs | 119.065μs | 118.442μs | 124.125μs | 3.494μs | 2.93%  | 40.61x |
| DecodeBench | decodePhp      | 0   | 10000 | 5   | 1,589,064b | 2.480μs   | 2.932μs   | 2.643μs   | 3.525μs   | 0.435μs | 14.83% | 1.00x  |
+-------------+----------------+-----+-------+-----+------------+-----------+-----------+-----------+-----------+---------+--------+--------+

```

# Conclusion

## Payload
ProtoBuf takes 2.24 times less space then JSON.

1000000 messages:

* Protobuf: 84 MB
* JSON: 188 MB

## Speed
JSON is 1.9 times faster then ProtoBuf.

1000000 messages:

* ProtoBuf: 3.38 seconds
* JSON: 1.78 seconds

## Considering network

To transfer the payload over network the following times are needed.

network with 10 MB/s for 1000000 messages:

* Protobuf: 9 seconds
* JSON: 19 seconds

=> through smaller payloads ProtoBuf wins on overall timings (serialization + network)