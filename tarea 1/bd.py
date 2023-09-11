import pyodbc
import csv
from datetime import datetime

# Establecer la conexión con la base de datos de SQL Server
usuario = "DESKTOP-C5ACT7O\Felipe"
contrasena = ""
baseDeDatos = "tarea1BD"
servidor = "DESKTOP-C5ACT7O\SQLEXPRESS"

# Conexión
cnxn = pyodbc.connect("DRIVER=ODBC Driver 17 for SQL Server; \
                      SERVER=" + servidor + ";\
                      DATABASE=" + baseDeDatos +";\
                      Trusted_Connection=yes;\
                      UID=" + usuario + ";\
                      PWD=" + contrasena +";")

# Cursor de conexion
print("Conexión establecida!")
print("Favor esperar mientras se cargan la información a la base de datos")
cursor = cnxn.cursor()

# 1
'''
Función que muestra todas las canciones que actualmente están en la tabla Reproducción,
permitiendo ordenar por fecha o por cantidad de veces reproducida, de manera ascendete o descendente.
'''
def mostrar_Reproduccion():
    cursor = cnxn.cursor()
    #Se crea una View para reproduccion
    cursor.execute('DROP VIEW IF EXISTS reproView')
    cursor.execute('''CREATE VIEW reproView AS
                    SELECT song_name, artist_name, fecha_reproduccion, cant_reproducciones
                    FROM reproduccion''')
    cursor.execute('''SELECT * FROM reproView''')
    empty = cursor.fetchall()
    if empty == []:
        print("No tienes canciones en reproduccion")
        return 0
    print("¿Desea ordenarlas por fecha o por cantidad de veces reproducida?")
    print("Escriba 1 para fecha y 2 para cantidad de veces reproducida")
    option_Order = input()
    if option_Order == "1":
        print("¿Desea ordenar de manera ascendente o descendente?")
        print("Escriba 1 para ascendente y 2 para descendente")
        option_Asc_Desc = input()
        if option_Asc_Desc == "1":
            cursor.execute('''SELECT * FROM reproView 
                            ORDER BY fecha_reproduccion ASC''')
        elif option_Asc_Desc == "2":
            cursor.execute('''SELECT * FROM reproView 
                            ORDER BY fecha_reproduccion DESC''')
        else:
            print("Opción no válida")
            return 0
    elif option_Order == "2":
        print("¿Desea ordenar de manera ascendente o descendente?")
        print("Escriba 1 para ascendente y 2 para descendente")
        option_Asc_Desc = input()
        if option_Asc_Desc == "1":
            cursor.execute('''SELECT * FROM reproView 
                            ORDER BY cant_reproducciones ASC''')
        elif option_Asc_Desc == "2":
            cursor.execute('''SELECT * FROM reproView 
                           ORDER BY cant_reproducciones DESC''')
        else:
            print("Opción no válida")
            return 0
    else:
        print("Opción no válida")
        return 0
    rows = cursor.fetchall()
    print("Canciones en su reproducción: ")
    for row in rows:
        print(row[0] + " - " + row[1])
    cnxn.commit()
    return 0

# 2
'''
Función que Muestra las canciones favoritas del usuario
'''
def mostrar_Favoritas():
    cursor = cnxn.cursor()
    cursor.execute('''SELECT * FROM lista_favoritos''')
    rows = cursor.fetchall()

    if rows == []:
        print("No tiene canciones favoritas")
    else:
        print("Nombre de canción - Nombre del artista")
        for row in rows:
            print(row[1] + " - " + row[2])
    cnxn.commit()
    return 0

# 3
'''
Función para hacer favorita una canción, el usuario debe ingresar el nombre de la canción que desea hacer favorita.
Tambien se actualiza en la tabla reproducción el campo favorito.
'''
def hacer_Favorita():
    cursor = cnxn.cursor()
    print("¿Que canción desea hacer favorita?")
    print("Debe de escribir el nombre de la canción")
    fecha_Actual = datetime.now().strftime("%Y-%m-%d")
    option_Fav = input()
    
    cursor.execute('''SELECT * FROM repositorio_musica
                    WHERE song_name = ?''', option_Fav)   
    empty = cursor.fetchall()
    if empty == []:
        print("La canción no existe en nuestro repositorio")
        return 0
    cancion = empty[0]
    #Se revisa si hay mas de una cancion con el mismo nombre, si es el caso, se le pregunta al usuario
    #cual de todas las canciones desea hacer favorita
    if len(empty) > 1:
        print("Hay mas de una cancion con ese nombre, ¿cual de las siguientes canciones deseas elegir?")
        print("Elija segun el numero que tiene cada opcion")
        i = 1
        for row in empty:
            print(f'{i}.- {row[3]} - {row[2]}')
            i += 1
        option = input()
        if option.isnumeric() == False:
            print("Opcion incorrecta")
            return 0
        elif int(option) >= 1 and int(option) < i:
            cancion = empty[int(option)-1]
        else:
            print("Opcion incorrecta")
            return 0

    # Ver si la cancion esta en la tabla reproduccion. Actualizar campo "favorito"
    cursor.execute('''SELECT * FROM reproduccion
                    WHERE song_name = ? AND artist_name = ?''',(cancion[3], cancion[2]))
    empty = cursor.fetchone()
    if empty != []:
        cursor.execute('''UPDATE reproduccion
                        SET favorito = 'True'
                        WHERE song_name = ? AND artist_name = ?''',(cancion[3], cancion[2]))
         
    cursor.execute('''SELECT * FROM lista_favoritos
                    WHERE song_name = ? AND artist_name = ?''',(cancion[3], cancion[2]))
    empty = cursor.fetchall() 
    if empty != []:
        print("La canción ya es favorita")
        return 0
        
    cursor.execute('''INSERT INTO lista_favoritos (id, song_name, artist_name, fecha_agregada) 
                    SELECT id, song_name, artist_name, ? 
                    FROM repositorio_musica 
                    WHERE song_name = ? AND artist_name = ?''', (fecha_Actual,cancion[3],cancion[2]))
    print("La canción " + str(option_Fav) + " ahora es favorita")
    cnxn.commit()
    return 0

# 4
'''
Función que remueve de la tabla favoritos una canción. El usuario debe ingresar el nombre de la canción.
Se actualiza tambien de la tabla reproducción, el campo favorito, si es que está esa canción y es favorita.
'''
def sacar_Favorita():
    cursor = cnxn.cursor()
    print("¿Que canción desea eliminar de favoritos?")
    print("Debe de escribir el nombre de la canción")
    option_Fav = input()

    cursor.execute('''SELECT * FROM repositorio_musica
                    WHERE song_name = ?''', option_Fav)
    empty = cursor.fetchall()
    if empty == []:
        print("La canción no existe")
        return 0
    cancion = empty[0]
    #Verifica si hay mas de una cancion con el mismo nombre
    if len(empty) > 1:
        print("Hay mas de una cancion con ese nombre, ¿cual de las siguientes canciones deseas elegir?")
        print("Elija segun el numero que tiene cada opcion")
        i = 1
        for row in empty:
            print(f'{i}.- {row[3]} - {row[2]}')
            i += 1
        option = input()
        if option.isnumeric() == False:
            print("Opcion incorrecta")
            return 0
        elif int(option) >= 1 and int(option) < i:
            cancion = empty[int(option)-1]
        else:
            print("Opcion incorrecta")
            return 0
    
    # Ver si la cancion esta en la tabla reproduccion. Eliminar "favorito"
    cursor.execute('''SELECT * FROM reproduccion
                    WHERE song_name = ? AND artist_name = ?''',(cancion[3], cancion[2]))
    empty = cursor.fetchall()
    if empty != []:
        print("canción en reproduccion")
        # Se actualiza "favorito" de tabla reproduccion a False
        cursor.execute('''UPDATE reproduccion
                        SET favorito = 'False'
                        WHERE song_name = ? AND artist_name = ?''',(cancion[3], cancion[2]))
    
    cursor.execute('''SELECT * FROM lista_favoritos
                    WHERE song_name = ? AND artist_name = ?''',(cancion[3], cancion[2]))
    empty = cursor.fetchall()
    if empty == []:
        print("La canción no es favorita")
        return 0
    
    cursor.execute('''DELETE FROM lista_favoritos 
                    WHERE song_name = ? AND artist_name = ?''',(cancion[3], cancion[2]))
    print("La canción " + str(option_Fav) + " ya no es favorita")
    cnxn.commit()
    return 0

# 5
'''
Función que simula la reproducción de una canción. Se solicita la usuario ingresar el nombre de la canción a reproducir.
Si es que la canción reproducida está en lista_favoritos, entonces se añade la canción como verdadero en el campo favorito
de la tabla reproducción
'''
def reproducir_Cancion():
    cursor = cnxn.cursor()
    print("¿Que canción desea reproducir?")
    print("Debe de escribir el nombre de la cancion")
    input_Song = input()

    # reproduccion tiene campos: id, song_name, artist_name, fecha_reproduccion, cant_reproducciones, favorito
    cursor.execute('''SELECT id, artist_name, song_name 
                    FROM repositorio_musica 
                    WHERE song_name = ?''',input_Song)
    empty = cursor.fetchall()
    
    if empty == []:
        print("La canción no está en nuestro repertorio")
        return 0
    
    cancion = empty[0]
    
    #Revisa si hay mas de una cancion con el mismo nombre
    if len(empty) > 1:
        print("Hay mas de una cancion con ese nombre, ¿cual de las siguientes canciones deseas elegir?")
        print("Elija segun el numero que tiene cada opcion")
        i = 1
        for row in empty:
            print(f'{i}.- {row[2]} - {row[1]}')
            i += 1
        option = input()
        if option.isnumeric() == False:
            print("Opcion incorrecta")
            return 0
        elif int(option) >= 1 and int(option) < i:
            cancion = empty[int(option)-1]
        else:
            print("Opcion incorrecta")
            return 0
    id = cancion[0]
    artist_Name = cancion[1]
    song_Name = cancion[2]

    # Se ve si la canción está en lista_favoritos
    cursor.execute('''SELECT * FROM lista_favoritos 
                    WHERE song_name = ? AND artist_name = ?''',(cancion[2], cancion[1]))
    if cursor.fetchone():
        fav = True
    else:
        fav = False
    
    cursor.execute('''SELECT cant_reproducciones 
                    FROM reproduccion 
                    WHERE song_name = ? AND artist_name = ?''',(cancion[2], cancion[1]))
    row = cursor.fetchone()

    if row:
        # Ya ha sido reproducida antes
        cant_reproducciones = row[0] + 1
        cursor.execute('''UPDATE reproduccion 
                        SET cant_reproducciones = ? 
                        WHERE song_name = ? and artist_name = ?''',(cant_reproducciones,cancion[2],cancion[1]))    
    else:
        # Primera vez reproducida
        hoy = datetime.now().strftime("%Y-%m-%d")
        cursor.execute('''INSERT INTO reproduccion (id, song_name, artist_name, fecha_reproduccion, cant_reproducciones, favorito)
                        VALUES (?,?,?,?,?,?) ''', (id, song_Name, artist_Name, hoy, 1, fav))
    
    print("Canción reproduciendose (〜￣△￣)〜")
    cnxn.commit()
    return 0

# 6
'''
Función que muestra la información de la canción elegida, disponible en la tabla Reproducción 
(nombre del artista, cantidad de veces reproducida, fecha de la primera, reproducción). Se solicita al usuario el nombre de la canción 
para realizar la búsqueda.
'''
def buscar_Reproduccion():
    cursor = cnxn.cursor()
    print("¿Que canción desea buscar en la tabla Reproduccion?")
    print("Debe de escribir el nombre de la canción")
    song_Name = input()
    
    cursor.execute('''SELECT * FROM reproduccion
                    WHERE song_name = ? ''',song_Name)
    empty = cursor.fetchall()
    if empty == []:
        print("La canción no se encuentra en sus Reproducciones")
        return 0
    cancion = empty[0]
    
    #Revisa si hay mas de una cancion con el mismo nombre
    if len(empty) > 1:
        print("Hay mas de una cancion con ese nombre, ¿cual de las siguientes canciones deseas elegir?")
        print("Elija segun el numero que tiene cada opcion")
        i = 1
        for row in empty:
            print(f'{i}.- {row[1]} - {row[2]}')
            i += 1
        option = input()
        if option.isnumeric() == False:
            print("Opcion incorrecta")
            return 0
        elif int(option) >= 1 and int(option) < i:
            cancion = empty[int(option)-1]
        else:
            print("Opcion incorrecta")
            return 0
        
    print("Nombre del artista: " + str(cancion[2]))
    print("Cantidad de veces reproducida: " + str(cancion[4]))
    print("Fecha de primera reproducción: " + str(cancion[3]))
    cnxn.commit()
    return 0

# 7 
'''
Función que muestra todas las canciones que el usuario haya escuchado por primera vez en los últimos X días (con X variable). 
Se solicita al usuario que ingrese esta fecha X.
'''
def ultimos_Dias():
    cursor = cnxn.cursor()
    print("Para mostrar todas las canciones que hayas escuchado por primera vez en los últimos X días, ingresa la fecha en el formato YYYY-MM-DD")
    fecha = input()
    cursor.execute('''SELECT * FROM reproduccion
                        WHERE fecha_reproduccion >= ?''',fecha)
    rows = cursor.fetchall()
    
    if rows == []:
        print("No hay canciones desde esa fecha")
        return 0
    print("Las canciones que has escuchado desde " + fecha + " son las siguientes")   
    for row in rows:
        print(row[1] + " - " + row[2])
    cnxn.commit()
    return 0

# 8 
'''
Función donde el usuario ingresa el nombre de una canción y mostrar al artista (si existe), 
Si varios artistas han escrito canciones con ese nombre, se muestran todos.
También al ingresar un artista, se mostran todas sus canciones.
'''
def buscar_NombreCancionArtista():
    cursor = cnxn.cursor()
    print("Ingresa 1 si deseas buscar una canción por su nombre o 2 si quieres buscar canciones por artista")
    option = input()

    if option == "1":
        print("Ingrese el nombre de la canción")
        song_Name = input()
        cursor.execute('''SELECT * FROM repositorio_musica
                        WHERE song_name = ?''',song_Name)
        rows = cursor.fetchall()
        if rows == []:
            print("No hay canciones con este nombre")
            return 0
        print("Todos los artistas son:")
        for row in rows:
            print(row[2])
    elif option == "2":
        print("Ingrese nombre de artista")
        artist_Name = input()
        cursor.execute('''SELECT * FROM repositorio_musica
                        WHERE artist_name = ?''',artist_Name)
        rows = cursor.fetchall()
        if rows == []:
            print("No hay artistas con este nombre")
            return 0
        print("Todas las canciones con este artista son:")
        for row in rows:
            print(row[3])
    else:
        print("canción no válida")
        return   
    cnxn.commit()
    return 0

# 9
'''
Función que muestra el Top 15 artistas con mayor cantidad total de veces en que sus canciones han estado en el top 10.
Es decir, la suma de las veces en que sus canciones han estado en el top
'''
def top_15():
    cursor = cnxn.cursor()
    print("A continuación, se mostrarán los top 15 artistas con mayor cantidad total de veces en que sus canciones han estado en el top 10")
    cursor.execute('''SELECT TOP 15 artist_name, SUM([top_10])   # Select top 15 selecciona los primeros 15
                    FROM repositorio_musica                      # SUM va sumando top 10
                    GROUP BY artist_name
                    ORDER BY SUM([top_10]) DESC''')
    rows = cursor.fetchall()
    for row in rows:
        print(row[0] + "con " + str(row[1]) + " veces en el top 10")
    cnxn.commit()
    return 0

# 10
'''
Función que muestra el peak position de un artista. 
Se muestra la posición más alta obtenida entre todas sus canciones. 
Se solicita al usuario ingresar el nombre del artista
'''
def peak_position():
    cursor = cnxn.cursor()
    print("Ingresa el nombre del artista que deseas conocer la posicion más alta obtenida entre todas sus canciones")
    artist_Name = input()
    cursor.execute('''SELECT * FROM repositorio_musica
                    WHERE artist_name = ?''',artist_Name)
    rows = cursor.fetchall()
    if rows == []:
        print("No hay artistas con este nombre")
        return 0
    # Se hace una subconsulta 
    cursor.execute('''SELECT song_name, peak_position FROM repositorio_musica 
                    WHERE artist_name = ? AND peak_position = (SELECT MIN(peak_position) 
                    FROM repositorio_musica WHERE artist_name = ?)''',artist_Name,artist_Name)
    row = cursor.fetchone()
    print("El peak position de " + str(artist_Name) + " es con la canción "+ str(row[0]) + " en el puesto número " + str(row[1]))
    cnxn.commit()

# 11
'''
Función que dado el nombre del artista que ingresa el usuario, se retorna el promedio de los streams considerando 
todas sus canciones.
'''
def promedio_Streams():
    cursor = cnxn.cursor()
    print("Ingresa el nombre del artista que deseas conocer el promedio de los streams considerando todas sus canciones")
    artist_Name = input()
    cursor.execute('''SELECT * FROM repositorio_musica
                    WHERE artist_name = ?''',artist_Name)
    rows = cursor.fetchall()
    if rows == []:
        print("No hay artistas con este nombre")
        return 0
    
    cursor.execute('''DROP FUNCTION IF EXISTS promedio_Streams''')
    cursor.execute('''CREATE FUNCTION promedio_Streams(@name VARCHAR(256)) 
                    RETURNS BIGINT
                    AS
                    BEGIN 
                        DECLARE @prom BIGINT
                        SET @prom = (SELECT AVG(CAST(total_streams AS BIGINT))
                                    FROM repositorio_musica 
                                    WHERE artist_name = @name)
                        RETURN @prom
                    END''')
    cursor.execute('''SELECT dbo.promedio_Streams(?)''', artist_Name)
    promedio = cursor.fetchone()[0]
    print("Promedio de streams de "+ artist_Name + " : " + str(promedio))
    cnxn.commit()
    return 0

# Abre archivo para leer los datos

arch = open("song.csv","r",encoding="utf8")

# Extrae filas del archivo CSV y skipea la primera fila

archivoCSV = csv.reader(arch)
next(archivoCSV)

# Crea las tablas

cursor.execute('DROP TABLE IF EXISTS repositorio_musica')
cursor.execute('''CREATE TABLE repositorio_musica (
                    id INT IDENTITY(1,1) PRIMARY KEY,
                    position INT,
                    artist_name VARCHAR(256),
                    song_name VARCHAR(256),
                    days INT,
                    top_10 INT,
                    peak_position INT,
                    peak_position_time VARCHAR(256),
                    peak_streams INT,
                    total_streams INT
                    )''')

cursor.execute('DROP TABLE IF EXISTS reproduccion')
cursor.execute('''CREATE TABLE reproduccion (
                    id INT PRIMARY KEY,
                    song_name VARCHAR(256),
                    artist_name VARCHAR(256),
                    fecha_reproduccion DATE,
                    cant_reproducciones INT,
                    favorito BIT
                    )''')

cursor.execute('DROP TABLE IF EXISTS lista_favoritos')
cursor.execute('''CREATE TABLE lista_favoritos (
                    id INT PRIMARY KEY,
                    song_name VARCHAR(256),
                    artist_name VARCHAR(256),
                    fecha_agregada DATE
                    )''')
                    
cnxn.commit()
# Carga datos en tabla repositorio musica
for linea in archivoCSV:
    string = linea[0]
    tupla = tuple(string.split(";"))
    cursor.execute('''INSERT INTO repositorio_musica(position, artist_name, song_name, days, top_10,
                    peak_position, peak_position_time, peak_streams, total_streams)
                    VALUES (?,?,?,?,?,?,?,?,?)''',tupla)
cnxn.commit()

# Ciclo para las opciones
x = True
while x:
    print(" ")
    print("¿Que desea hacer?")
    print("Para seleccionar una de las opciones, debe de escribir el numero que esta al lado de cada opcion")
    print("1.- Mostrar Reproduccion")
    print("2.- Mostrar canciones favoritas")
    print("3.- Hacer favorita una cancion")
    print("4.- Sacar de favoritas una cancion")
    print("5.- Reproducir una cancion")
    print("6.- Buscar una cancion")
    print("7.- Mostrar todas las canciones que has escuchado por primera vez ultimamente")
    print("8.- Buscar por nombre de canción y por artista")
    print("9.- Top 15 artistas con mayor cantidad total de veces en que sus canciones han estado en el top 10")
    print("10.- Peak position de un artista")
    print("11.- Promedio de streams totales de un artista")
    print("0.- Salirse del menu")
    print(" ")
    option = input()

    if option == "1":
        # lista
        mostrar_Reproduccion()    
    elif option == "2":
        # lista
        mostrar_Favoritas()
    elif option == "3":
        # lista
        hacer_Favorita()
    elif option == "4":
        # lista
        sacar_Favorita()
    elif option == "5":
        # lista
        reproducir_Cancion()
    elif option == "6":
        # lista
        buscar_Reproduccion()
    elif option == "7":
        # lista
        ultimos_Dias()
    elif option == "8":
        # lista
        buscar_NombreCancionArtista()
    elif option == "9":
        # lista
        top_15()
    elif option == "10":
        # lista
        peak_position()
    elif option == "11":
        # lista
        promedio_Streams()
    elif option == "0":
        x = False
    else:
        print("Por favor ingrese algun numero del menu")

cnxn.commit()
cnxn.close()
arch.close()