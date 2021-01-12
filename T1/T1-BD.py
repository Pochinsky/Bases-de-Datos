#librerias utilizadas
import cx_Oracle
import random
import math
import datetime
import sys
import os
import tabulate

"""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""
*Clases utilizadas
*Utilizacion de clases para facilitar reescritura del programa
*Programa primero fue escrito en varios archivos .py
"""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""""
class Function:
    """
    Método:         extractCSV
    Parámetros:     No recibe
    Retorno:        Lista de listas
    Funcionalidad:  Extrae la información necesaria para la construcción de la tabla POYO en una lista de listas
    """
    def extractCSV(self):
        file = open('pokemon.csv','r')
        pokemones = list()
        aux = list()

        for line in file:
            line = line.strip().split(',')
            if line[0] != '#':
                aux.append(int(line[0]))#Numero_Pokedex
                aux.append(line[1])#Nombre_Pokemon
                aux.append(line[2])#Tipo_1
                aux.append(line[3])#Tipo_2
                aux.append(int(line[4]))#HP_total
                aux.append(line[12])#Legendary
                pokemones.append(aux)
                aux = list()
        file.close()
        return pokemones

    """
    Método:         createTable
    Parámetros:     Objeto Cursor
    Retorno:        No retorna
    Funcionalidad:  Crea una nueva tabla en la base de datos
    """
    def createTable(self,connection,name):
        cursor = connection.cursor()
        if name=='POYO':
            cursor.execute('CREATE TABLE POYO (Numero_Pokedex number, Nombre_Pokemon VARCHAR(50), Tipo_1 VARCHAR(50), Tipo_2 VARCHAR(50), HP_Total number, Legendario VARCHAR(5))')
        elif name=='SANSANITO_POKEMON':
            cursor.execute('CREATE TABLE SANSANITO_POKEMON (ID number, Numero_Pokedex number, Nombre_Pokemon VARCHAR(50), Tipo_1 VARCHAR(50), Tipo_2 VARCHAR(50), HP_Actual number, HP_Total number, Legendario VARCHAR(5), Estado VARCHAR(10),Fecha_Hora_De_Ingreso TIMESTAMP(0), Prioridad number)')
        cursor.close()
    """
    Método:         dropTable
    Parámetros:     Objeto Cursor, string name
    Retorno:        No retorna
    Funcionalidad:  Elimina la tabla cuyo nombre es igual al ingresado como paramentro
    """
    def dropTable(self,connection,name):
        cursor = connection.cursor()
        cursor.execute('DROP TABLE {}'.format(name))
        cursor.close()

    """
    Método:         existTable
    Parámetros:     Objeto Cursor, string name
    Retorno:        Valor booleano
    Funcionalidad:  Verifica si una tabla existe en la base de datos, retornando True si es asi y False en caso contrario
    """
    def existTable(self,connection,name):
        cursor = connection.cursor()
        try:
            cursor.execute("SELECT * FROM {}".format(name))
            cursor.close()
            return True
        except cx_Oracle.DatabaseError as e:
            var = e.args[0]
            if var.code == 942: ## Only catch ORA-00942: table or view does not exist error
                cursor.close()
                return False

    """
    Método:         isEmpty
    Parámetros:     Objeto Cursor, string name
    Retorno:        Valor booleano
    Funcionalidad:  Verfica si una tabla está vacía o no, retornando True o False respectivamente
    """
    def tableIsEmpty(self,connection,name):
        cursor = connection.cursor()
        cursor.execute('SELECT * FROM {}'.format(name))
        aux = 0
        for result in cursor:
            aux += 1
        if aux==0:
            cursor.close()
            return True
        else:
            cursor.close()
            return False

    """
    Método:         fillPOYO
    Parámetros:     Objeto Connection, lista de listas
    Retorno:        No retorna
    Funcionalidad:  Rellena la tabla POYO con los datos extraidos del archivo pokemon.csv
    """
    def fillPOYO(self,connection,lista_pokemones):
        cursor = connection.cursor()
        for lista in lista_pokemones:
            cursor.execute('INSERT INTO POYO (Numero_Pokedex, Nombre_Pokemon, Tipo_1, Tipo_2, HP_Total, Legendario) '+
                           'VALUES (:1,:2,:3,:4,:5,:6)',lista)
            connection.commit()
        cursor.close()

    """
    Método:         randomHP
    Parámetros:     int maxHP
    Retorno:        int
    Funcionalidad:  Crea un HP actual aleatoriamente
    """
    def randomHP(self,maxHP):
        return random.randint(1,maxHP)

    """
    Método:         randomState
    Parámetros:     No recibe
    Retorno:        string
    Funcionalidad:  crea un estado de forma aleatoria
    """
    def randomState(self):
        number = random.randint(1,6)
        if number==1:
            return 'Envenenado'
        elif number==2:
            return 'Paralizado'
        elif number==3:
            return 'Quemado'
        elif number==4:
            return 'Dormido'
        elif number==5:
            return 'Congelado'
        elif number==6:
            return ''

    """
    Método:         randomDate
    Parámetros:     No recibe
    Retorno:        string
    Funcionalidad:  Genera una fecha y hora aleatoria dentro del año 2020
    """
    def randomDate(self):
        date_and_time = ''
        #dia
        number_day = random.randint(1,28)
        if number_day<10:
            day = '0'+str(number_day)
        else:
            day = str(number_day)
        #mes
        number_month = random.randint(1,12)
        if number_month<10:
            month = '0' + str(number_month)
        else:
            month = str(number_month)
        #año
        year = '2020'
        #hora
        number_hour = random.randint(0,23)
        if number_hour<10:
            hour = '0'+str(number_hour)
        else:
            hour = str(number_hour)
        #minuto
        number_minute = random.randint(0,59)
        if number_minute<10:
            minute = '0'+str(number_minute)
        else:
            minute = str(number_minute)
        #segundo
        number_second = random.randint(0,59)
        if number_second<10:
            second = '0'+str(number_second)
        else:
            second = str(number_second)
        date_and_time = day + '-' + month + '-' + year + ' ' + hour + ':' + minute + ':' + second
        return date_and_time

    """
    Método:         randomID
    Parámetros:     No recibe
    Retorno:        int
    Funcionalidad:  Genera una ID aleatoria
    """
    def randomID(self):
        return random.randint(1,800)

    """
    Método:         allRecords
    Parámetros:     Objeto Cursor, string name
    Retorno:        Lista de tuplas
    Funcionalidad:  Extrae todos los registros de la tabla cuyo nombre es igual al ingresado como parametro
    """
    def allRecords(self,connection,name):
        cursor = connection.cursor()
        cursor.execute('SELECT * FROM {}'.format(name))
        records = cursor.fetchall()
        cursor.close()
        return records

    """
    Método:         createSansanito
    Parámetros:     Objeto Connection
    Retorno:        No retorna
    Funcionalidad:  Crea la tabla SANSANITO_POKEMON con registros aleatorios extraidos de la tabla POYO
                    además de añadir las características faltantes también de forma aleatoria
    """
    def createSansanito(self,connection):
        cursor = connection.cursor()
        records_poyo = self.allRecords(connection,'POYO')
        self.createTable(connection,'SANSANITO_POKEMON')
        capacidad = 0
        aux = list()
        while capacidad < 50:
            i = random.randint(0,799)
            if i not in aux:
                info_pokemon = records_poyo[i]
                pokedex,name,type1,type2,maxHP,legendary = info_pokemon
                ID = self.randomID()
                actualHP = self.randomHP(maxHP)
                state = self.randomState()
                date = self.randomDate()
                if state != '':
                    priority = maxHP-actualHP+10
                else:
                    priority = maxHP-actualHP
                if legendary=='True':
                    if capacidad+5 <= 50:
                        cursor.execute('INSERT INTO SANSANITO_POKEMON (ID, Numero_Pokedex, Nombre_Pokemon, Tipo_1, Tipo_2, HP_Actual, HP_Total, Legendario, Estado, Fecha_Hora_De_Ingreso, Prioridad) '+
                                       'VALUES (:1,:2,:3,:4,:5,:6,:7,:8,:9,:10,:11)',[ID,pokedex,name,type1,type2,actualHP,maxHP,legendary,state,date,priority])
                        connection.commit()
                        capacidad += 5
                else:
                    cursor.execute('INSERT INTO SANSANITO_POKEMON (ID, Numero_Pokedex, Nombre_Pokemon, Tipo_1, Tipo_2, HP_Actual, HP_Total, Legendario, Estado, Fecha_Hora_De_Ingreso, Prioridad) '+
                                   'VALUES (:1,:2,:3,:4,:5,:6,:7,:8,:9,:10,:11)',[ID,pokedex,name,type1,type2,actualHP,maxHP,legendary,state,date,priority])
                    connection.commit()
                    capacidad += 1
                aux.append(i)
        cursor.close()

    """
    Método:         capacidad
    Parámetros:     Lista de tuplas
    Retorno:        int
    Funcionalidad:  Calcula la capacidad que hay en SANSANITO_POKEMON
    """
    def capacidad(self,lista_sansanito):
        capacidad = 0
        for tupla in lista_sansanito:
            if  tupla[7] == 'True':
                capacidad += 5
            else:
                capacidad += 1
        return capacidad

    """
    Método:         lessPriority
    Parámetros:     Lista de tuplas
    Retorno:        tupla
    Funcionalidad:  extrae toda la información del pokemon con menor prioridad
    """
    def lessPriority(self,lista_sansanito):
        less = inf
        pokemon = tuple()
        for tupla in lista_sansanito:
            if tupla[10] < inf:
                less = tupla[10]
                pokemon = tupla
        return pokemon

    """
    Método:         repeat_name
    Parámetros:     Objeto cursor, string name
    Retorno:        Valor booleano
    Funcionalidad:  Verifica si un nombre de un pokemon está repetido en la tabla SANSANITO_POKEMON,
                    de ser así retorna True y caso contrario retorna False
    """
    def repeat_name(self,connection,name):
        cursor = connection.cursor()
        cursor.execute('SELECT * FROM SANSANITO_POKEMON')
        info = cursor.fetchall()
        cont = 0
        for tupla in info:
            if name in tupla:
                cont += 1
        if cont == 1:
            cursor.close()
            return False
        elif cont > 1:
            cursor.close()
            return True

class Crud:
    """
    Método:         CREATE
    Parámetros:     Objeto connection, string name_pokemon, int actualHP, string state, Objeto Function
    Retorno:        Valor booleano
    Funcionalidad:  inserta un nuevo pokemon dependiendo de las condiciones actuales de SANSANITO_POKEMON y
                    retorna True si se inserta y False si no
    """
    def CREATE(self,connection,name_pokemon,actualHP,state,fun):
        cursor = connection.cursor()
        cursor.execute('SELECT * FROM SANSANITO_POKEMON')
        info_sansanito = cursor.fetchall()
        cursor.execute('SELECT * FROM POYO')
        info_poyo = cursor.fetchall()
        if fun.capacidad(info_sansanito) == 50:
            #inicio recoleccion de informacion necesaria para crear el registro
            for tupla in info_poyo:
                if name_pokemon in tupla:
                    pokedex,name,type1,type2,maxHP,legend = tupla
                    break
            priority = maxHP-actualHP
            date = datetime.datetime.now()
            ID = fun.randomID()
            aux = list()
            for tupla in info_sansanito:
                aux.append(tupla[0])
            flag = True
            while flag:
                if ID in aux:
                    ID = fun.randomID()
                else:
                    flag = False
            #fin de recoleccion de informacion necesaria para crear el registro
            #separacion legendarios y no legendarios
            legends = list()
            normals = list()
            for tupla in info_sansanito:
                if tupla[7] == 'True':
                    legends.append(tupla)
                else:
                    normals.append(tupla)
            #extraccion del pokemon con menor prioridad segun corresponda
            tupla_pokemon = tuple()
            aux = math.inf
            if legend == 'True':
                for tupla in legends:
                    if tupla[10] < aux:
                        aux = tupla[10]
                        tupla_pokemon = tupla
            else:
                for tupla in normals:
                    if tupla[10] < aux:
                        aux = tupla[10]
                        tupla_pokemon = tupla
            #comparacion de prioridad con el pokemon que se quiere insertar
            #insercion y borrado segun corresponda
            if priority > tupla_pokemon[10]:
                cursor.execute("DELETE FROM SANSANITO_POKEMON WHERE ID='{}'".format(tupla_pokemon[0]))
                connection.commit()
                cursor.execute('INSERT INTO SANSANITO_POKEMON (ID, Numero_Pokedex, Nombre_Pokemon, Tipo_1, Tipo_2, HP_Actual, HP_Total, Legendario, Estado, Fecha_Hora_De_Ingreso, Prioridad) '+
                               'VALUES (:1,:2,:3,:4,:5,:6,:7,:8,:9,:10,:11)',[ID,pokedex,name,type1,type2,actualHP,maxHP,legend,state,date,priority])
                connection.commit()
                cursor.close()
                return True
            else:
                cursor.close()
                return False
        else:
            #inicio recoleccion de informacion necesaria para crear el registro
            for tupla in info_poyo:
                if name_pokemon in tupla:
                    pokedex,name,type1,type2,maxHP,legend = tupla
                    break
            priority = maxHP-actualHP
            date = datetime.datetime.now()
            ID = fun.randomID()
            aux = list()
            for tupla in info_sansanito:
                aux.append(tupla[0])
            flag = True
            while flag:
                if ID in aux:
                    ID = fun.randomID()
                else:
                    flag = False
            #fin de recoleccion de informacion necesaria para crear el registro
            if legend == 'False':
                cursor.execute('INSERT INTO SANSANITO_POKEMON (ID, Numero_Pokedex, Nombre_Pokemon, Tipo_1, Tipo_2, HP_Actual, HP_Total, Legendario, Estado, Fecha_Hora_De_Ingreso, Prioridad) '+
                               'VALUES (:1,:2,:3,:4,:5,:6,:7,:8,:9,:10,:11)',[ID,pokedex,name,type1,type2,actualHP,maxHP,legend,state,date,priority])
                connection.commit()
                cursor.close()
                return True
            else:
                if fun.capacidad(info_sansanito) <= 45:
                    cursor.execute('INSERT INTO SANSANITO_POKEMON (ID, Numero_Pokedex, Nombre_Pokemon, Tipo_1, Tipo_2, HP_Actual, HP_Total, Legendario, Estado, Fecha_Hora_De_Ingreso, Prioridad) '+
                                   'VALUES (:1,:2,:3,:4,:5,:6,:7,:8,:9,:10,:11)',[ID,pokedex,name,type1,type2,actualHP,maxHP,legend,state,date,priority])
                    connection.commit()
                    cursor.close()
                    return True
                else:
                    #separacion de los legendarios
                    legends = list()
                    for tupla in info_sansanito:
                        if tupla[7] == 'True':
                            legends.append(tupla)
                    #extraccion del pokemon con menor prioridad
                    tupla_pokemon = tuple()
                    aux = math.inf
                    if legend == 'True':
                        for tupla in legends:
                            if tupla[10] < aux:
                                aux = tupla[10]
                                tupla_pokemon = tupla
                    #comparacion de prioridad con el pokemon que se quiere insertar
                    #insercion y borrado segun corresponda
                    if priority > tupla_pokemon[10]:
                        cursor.execute("DELETE FROM SANSANITO_POKEMON WHERE ID='{}'".format(tupla_pokemon[0]))
                        connection.commit()
                        cursor.execute('INSERT INTO SANSANITO_POKEMON (ID, Numero_Pokedex, Nombre_Pokemon, Tipo_1, Tipo_2, HP_Actual, HP_Total, Legendario, Estado, Fecha_Hora_De_Ingreso, Prioridad) '+
                                       'VALUES (:1,:2,:3,:4,:5,:6,:7,:8,:9,:10,:11)',[ID,pokedex,name,type1,type2,actualHP,maxHP,legend,state,date,priority])
                        connection.commit()
                        cursor.close()
                        return True
                    else:
                        cursor.close()
                        return False

    """
    Método:         READ
    Parámetros:     Objeto Cursor, Lista de ints
    Retorno:        Lista de tuplas
    Funcionalidad:  Extrae la información que se desea consultar
    """
    def READ(self,connection,columns):
        cursor = connection.cursor()
        table = list()
        for col in columns:
            aux = list()
            if col==1:#ID
                cursor.execute('SELECT ID FROM SANSANITO_POKEMON')
                IDs = cursor.fetchall()
                for tupla in IDs:
                    aux.append(tupla[0])
                table.append(tuple(aux))
            elif col==2:#POKEDEX
                cursor.execute('SELECT Numero_Pokedex FROM SANSANITO_POKEMON')
                numbers = cursor.fetchall()
                for tupla in numbers:
                    aux.append(tupla[0])
                table.append(tuple(aux))
            elif col==3:#NOMBRE
                cursor.execute('SELECT Nombre_Pokemon FROM SANSANITO_POKEMON')
                names = cursor.fetchall()
                for tupla in names:
                    aux.append(tupla[0])
                table.append(tuple(aux))
            elif col==4:#TIPO 1
                cursor.execute('SELECT Tipo_1 FROM SANSANITO_POKEMON')
                types = cursor.fetchall()
                for tupla in types:
                    aux.append(tupla[0])
                table.append(tuple(aux))
            elif col==5:#TIPO 2
                cursor.execute('SELECT Tipo_2 FROM SANSANITO_POKEMON')
                types = cursor.fetchall()
                for tupla in types:
                    aux.append(tupla[0])
                table.append(tuple(aux))
            elif col==6:#HP ACTUAL
                cursor.execute('SELECT HP_Actual FROM SANSANITO_POKEMON')
                HPs = cursor.fetchall()
                for tupla in HPs:
                    aux.append(tupla[0])
                table.append(tuple(aux))
            elif col==7:#HP MAX
                cursor.execute('SELECT HP_Total FROM SANSANITO_POKEMON')
                HPs = cursor.fetchall()
                for tupla in HPs:
                    aux.append(tupla[0])
                table.append(tuple(aux))
            elif col==8:#LEGENDARIO
                cursor.execute('SELECT Legendario FROM SANSANITO_POKEMON')
                legends = cursor.fetchall()
                for tupla in legends:
                    aux.append(tupla[0])
                table.append(tuple(aux))
            elif col==9:#ESTADO
                cursor.execute('SELECT Estado FROM SANSANITO_POKEMON')
                states = cursor.fetchall()
                for tupla in states:
                    aux.append(tupla[0])
                table.append(tuple(aux))
            elif col==10:#DATETIME
                cursor.execute('SELECT Fecha_Hora_De_Ingreso FROM SANSANITO_POKEMON')
                dates = cursor.fetchall()
                for tupla in dates:
                    aux.append(tupla[0].strftime("%m/%d/%Y - %H:%M:%S"))
                table.append(tuple(aux))
            elif col==11:#PRIORIDAD
                cursor.execute('SELECT Prioridad FROM SANSANITO_POKEMON')
                prioritys = cursor.fetchall()
                for tupla in prioritys:
                    aux.append(tupla[0])
                table.append(tuple(aux))
        cursor.close()
        return table

    """
    Método:         UPDATE
    Parámetros:     Objeto Connection, Diccionario columns_values, string name, Objeto Function
    Retorno:        No retorna
    Funcionalidad:  Actualiza la información de un pokemon segun nombre y si el nombre se repite, segun ID
    """
    def UPDATE(self,connection,columns_values,name,fun):
        cursor = connection.cursor()
        if not fun.repeat_name(connection,name):
            cols = list(columns_values.keys())
            for col in cols:
                if col==1:
                    cursor.execute("UPDATE SANSANITO_POKEMON SET HP_Actual='{}' WHERE Nombre_Pokemon='{}'".format(columns_values[col],name))
                    connection.commit()
                elif col==2:
                    cursor.execute("UPDATE SANSANITO_POKEMON SET Estado='{}' WHERE Nombre_Pokemon='{}'".format(columns_values[col],name))
                    connection.commit()
                elif col==3:
                    cursor.execute("UPDATE SANSANITO_POKEMON SET Prioridad='{}' WHERE Nombre_Pokemon='{}'".format(columns_values[col],name))
                    connection.commit()
        else:
            os.system('cls')
            print('\n===== BASE DE DATOS SANSANITO_POKEMON =====\n')
            print('\t'+name+': Pokemon repetido.')
            ID = int(input('\tDigite la ID de '+name+': '))
            cols = list(columns_values.keys())
            for col in cols:
                if col==1:
                    cursor.execute("UPDATE SANSANITO_POKEMON SET HP_Actual='{}' WHERE ID='{}'".format(columns_values[col],ID))
                    connection.commit()
                elif col==2:
                    cursor.execute("UPDATE SANSANITO_POKEMON SET Estado='{}' WHERE ID='{}'".format(columns_values[col],ID))
                    connection.commit()
                elif col==3:
                    cursor.execute("UPDATE SANSANITO_POKEMON SET Prioridad='{}' WHERE ID='{}'".format(columns_values[col],ID))
                    connection.commit()
        cursor.close()

    """
    Método:         DELETE
    Parámetros:     Objeto Connection, string name_pokemon, Objeto fun
    Retorno:        No retorna
    Funcionalidad:  Elimina un pokemon de la tabla SANSANITO_POKEMON segun nombre y si el nombre se repite, segun ID
    """
    def DELETE(self,connection,name_pokemon,fun):
        cursor = connection.cursor()
        if not fun.repeat_name(connection,name_pokemon):
            cursor.execute("DELETE FROM SANSANITO_POKEMON WHERE Nombre_Pokemon='{}'".format(name_pokemon))
            connection.commit()
        else:
            os.system('cls')
            print('\n===== BASE DE DATOS SANSANITO_POKEMON =====\n')
            print('\t'+name_pokemon+': Pokemon repetido.')
            ID = int(input('\tDigite la ID de '+name_pokemon+': '))
            cursor.execute("DELETE FROM SANSANITO_POKEMON WHERE ID='{}'".format(ID))
            connection.commit()
        cursor.close()

class Query:
    """
    Método:         top10
    Parámetros:     Objeto Cursor
    Retorno:        Lista
    Funcionalidad:  Obtiene los 10 pokemones con mayor prioridad
    """
    def top10(self,connection):
        cursor = connection.cursor()
        top = list()
        cursor.execute('SELECT * FROM SANSANITO_POKEMON ORDER BY Prioridad')
        records = cursor.fetchall()
        size = len(records)
        if size>=10:
            top.append(records[size-1][2])
            top.append(records[size-2][2])
            top.append(records[size-3][2])
            top.append(records[size-4][2])
            top.append(records[size-5][2])
            top.append(records[size-6][2])
            top.append(records[size-7][2])
            top.append(records[size-8][2])
            top.append(records[size-9][2])
            top.append(records[size-10][2])
        else:
            i = size-1
            while i>=0:
                top.append(records[i][2])
                i-=1
        cursor.close()
        return top

    """
    Método:         bottom10
    Parámetros:     Objeto Cursor
    Retorno:        Lista
    Funcionalidad:  Obtiene los 10 pokemones con menor prioridad
    """
    def bottom10(self,connection):
        cursor = connection.cursor()
        bottom = list()
        cursor.execute('SELECT * FROM SANSANITO_POKEMON ORDER BY Prioridad')
        records = cursor.fetchall()
        size = len(records)
        if size >=10:
            bottom.append(records[0][2])
            bottom.append(records[1][2])
            bottom.append(records[2][2])
            bottom.append(records[3][2])
            bottom.append(records[4][2])
            bottom.append(records[5][2])
            bottom.append(records[6][2])
            bottom.append(records[7][2])
            bottom.append(records[8][2])
            bottom.append(records[9][2])
        else:
            for tupla in records:
                bottom.append(tupla[2])
        cursor.close()
        return bottom

    """
    Método:         states
    Parámetros:     Objeto Cursor, string state
    Retorno:        Lista
    Funcionalidad:  Obtiene todos los pokemones con un mismo estado
    """
    def states(self,connection,state):
        cursor = connection.cursor()
        pokemones = list()
        cursor.execute('SELECT * FROM SANSANITO_POKEMON')
        records = cursor.fetchall()
        for tupla in records:
            if state in tupla:
                pokemones.append(tupla[2])
        cursor.close()
        return pokemones

    """
    Método:         moreTime
    Parámetros:     Objeto Cursor
    Retorno:        string
    Funcionalidad:  Retorna el nombre del pokemon que lleva más tiempo en la tabla.
    """
    def moreTime(self,connection):
        cursor = connection.cursor()
        pokemon = ''
        date = None
        time = 0
        cursor.execute('SELECT * FROM SANSANITO_POKEMON')
        records = cursor.fetchall()
        actual_date = datetime.datetime.now()
        for tupla in records:
            if records.index(tupla)==0:
                pokemon = tupla[2]
                date = tupla[9]
                time = actual_date-date
            else:
                delta = actual_date-tupla[9]
                if delta > time:
                    pokemon = tupla[2]
                    date = tupla[9]
                    time = delta
        cursor.close()
        return pokemon

    """
    Método:         mostRepeat
    Parámetros:     Objeto Cursor
    Retorno:        string
    Funcionalidad:  Obtiene el pokemon más repetido según nombre
    """
    def mostRepeat(self,connection):
        cursor = connection.cursor()
        cursor.execute('SELECT * FROM SANSANITO_POKEMON')
        info = cursor.fetchall()
        names = dict()
        for tupla in info:
            name = tupla[2]
            if name not in names:
                names[name] = 1
            else:
                names[name] += 1
        pokemon = ''
        repeats = 0
        for key in names:
            if pokemon == '' and repeats == 0:
                pokemon = key
                repeats = names[key]
            else:
                if names[key] > repeats:
                    pokemon = key
                    repeats = names[key]
        cursor.close()
        return pokemon

    """
    Método:         allInfo
    Parámetros:     Objeto Cursor
    Retorno:        Lista de tuplas
    Funcionalidad:  Extrae el nombre, hp actual, hp max y prioridad de todos los pokemones de SANSANITO_POKEMON
    """
    def allInfo(self,connection):
        cursor = connection.cursor()
        info = list()
        aux = list()
        cursor.execute('SELECT * FROM SANSANITO_POKEMON')
        records = cursor.fetchall()
        for tupla in records:
            aux.append(tupla[10])
            aux.append(tupla[2])
            aux.append(tupla[5])
            aux.append(tupla[6])
            info.append(tuple(aux))
            aux = list()
        info.sort()
        cursor.close()
        return info

class Menu:
    """
    Método:         main_menu
    Parámetros:     No recibe
    Retorno:        int
    Funcionalidad:  Da a elegir al usuario entre realizar una query u operacion CRUD, retornando la opción elegida.
    """
    def main_menu(self):
        os.system('cls')
        print('\n===== BASE DE DATOS SANSANITO_POKEMON =====\n')
        print('\tOpciones:\n')
        print('\t1. Realizar query.')
        print('\t2. Realizar operacion CRUD.')
        print('\t3. Salir de la base de datos.\n')
        opcion = int(input('\tDigite numero de opcion: '))
        return opcion

    """
    Método:         querys_menu
    Parámetros:     No recibe
    Retorno:        int
    Funcionalidad:  Da a elegir al usuario una query a realizar, retornando la query elegida.
    """
    def querys_menu(self):
        os.system('cls')
        print('\n===== BASE DE DATOS SANSANITO_POKEMON =====\n')
        print('\tQuerys:\n')
        print('\t1. Ingresar un pokemon.')
        print('\t2. Los 10 pokemones con mayor prioridad.')
        print('\t3. Los 10 pokemones con menor prioridad.')
        print('\t4. Todos los pokemones con un estado especifico.')
        print('\t5. Todos los pokemones legendarios.')
        print('\t6. El pokemon que lleva más tiempo ingresado.')
        print('\t7. Nombre del pokemon mas repetido.')
        print('\t8. Nombre, HP, HP max y prioridad de todos los pokemones (ordenado por prioridad).')
        print('\t9. Volver al menu principal.\n')
        opcion = int(input('\tDigite numero de query a realizar: '))
        return opcion

    """
    Método:         CRUD_menu
    Parámetros:     No recibe
    Retorno:        int
    Funcionalidad:  Da a elegir al usuario una operacion CRUD a realizar, retornando la operacion elegida.
    """
    def CRUD_menu(self):
        os.system('cls')
        print('\n===== BASE DE DATOS SANSANITO_POKEMON =====\n')
        print('\tOperaciones CRUD:\n')
        print('\t1. CREATE.')
        print('\t2. READ.')
        print('\t3. UPDATE.')
        print('\t4. DELETE.')
        print('\t5. Volver al menu principal.\n')
        opcion = int(input('\tDigite numero de operacion CRUD a realizar: '))
        return opcion

    """
    Método:         ingresar_pokemon_menu
    Parámetros:     Objeto Connection, Objeto Crud, Objeto Function
    Retorno:        No retorna
    Funcionalidad:  pide los datos necesarios al usuario para ingresar un pokemon a la tabla, siempre y cuando se pueda-
    """
    def into_pokemon_menu(self,connection,crud,fun):
        os.system('cls')
        print('\n===== BASE DE DATOS SANSANITO_POKEMON =====\n')
        name = input('\tIngrese nombre del pokemon: ')
        actualHP = int(input('\tIngrese la vida actual de '+name+": "))
        state = input('\tIngrese el estado de '+name+" (enter para no ingresar estado): ")
        flag = crud.CREATE(connection,name,actualHP,state,fun)
        os.system('cls')
        print('\n===== BASE DE DATOS SANSANITO_POKEMON =====\n')
        if flag:
            print('\n\tSe ha ingresado a '+name+" correctamente.")
        else:
            print('\n\tNo se ha podido ingresar a '+name+".")
        input('\n\npresione cualquier tecla para continuar.')

    """
    Método:         repeat_menu
    Parámetros:     string
    Retorno:        Valor booleano
    Funcionalidad:  Dado un nombre representativo de un menu, pregunta al usuario si quiere realizar otra opcion de este.
    """
    def repeat_menu(self,menu):
        os.system('cls')
        print('\n===== BASE DE DATOS SANSANITO_POKEMON =====\n')
        if menu == 'query':
            print('\t¿Desea realizar otra query?')
            print('\t1. Si.')
            print('\t2. No.\n')
            opcion = int(input('\tDigite numero de opcion a elegir: '))
            if opcion == 1:
                return True
            elif opcion == 2:
                return False
        elif menu == 'CRUD':
            print('\t¿Desea realizar otra operacion CRUD?')
            print('\t1. Si.')
            print('\t2. No.\n')
            opcion = int(input('\tDigite numero de opcion a elegir: '))
            if opcion == 1:
                return True
            elif opcion == 2:
                return False

    """
    Método:         show_top10
    Parámetros:     Objeto Cursor, Objeto Query
    Retorno:        No retorna
    Funcionalidad:  imprime los 10 pokemones con mayor prioridad.
    """
    def show_top10(self,connection,qr):
        cursor = connection.cursor()
        os.system('cls')
        top = qr.top10(connection)
        size =  len(top)
        print('\n===== BASE DE DATOS SANSANITO_POKEMON =====\n')
        print('\tLos 10 pokemones con mayor prioridad son:\n')
        i = 0
        while i < size:
            if i != 9:
                print('\t'+str(i+1)+".  "+top[i]+".")
            else:
                print('\t'+str(i+1)+". "+top[i]+".")
            i += 1
        cursor.close()
        input('\n\npresione cualquier tecla para continuar.')
    """
    Método:         show_bottom10
    Parámetros:     Objeto Cursor, Objeto Query
    Retorno:        No retorna
    Funcionalidad:  Imprime los 10 pokemones con menor prioridad.
    """
    def show_bottom10(self,connection,qr):
        cursor = connection.cursor()
        os.system('cls')
        bottom = qr.bottom10(connection)
        size = len(bottom)
        i = 0
        print('\n===== BASE DE DATOS SANSANITO_POKEMON =====\n')
        print('\tLos 10 pokemones con menor prioridad son:\n')
        while i < size:
            if i != 9:
                print('\t'+str(i+1)+".  "+bottom[i]+".")
            else:
                print('\t'+str(i+1)+". "+bottom[i]+".")
            i += 1
        cursor.close()
        input('\n\npresione cualquier tecla para continuar.')

    """
    Método:         show_state_pokemons
    Parámetros:     Objeto Cursor, Objeto Query
    Retorno:        No retorna
    Funcionalidad:  Pide al usuario un estado específico y muestra por pantalla los pokemones con dicho estado.
    """
    def show_state_pokemons(self,connection,qr):
        cursor = connection.cursor()
        os.system('cls')
        print('\n===== BASE DE DATOS SANSANITO_POKEMON =====\n')
        state = input('\tIngrese estado de los pokemones a mostrar: ')
        pokemones = qr.states(connection,state)
        size = len(pokemones)
        os.system('cls')
        print('\n===== BASE DE DATOS SANSANITO_POKEMON =====\n')
        print('\tLos pokemones con el estado "'+state+'" son:\n')
        i=0
        while i < size:
            print('\t'+str(i+1)+'. '+pokemones[i]+'.')
            i += 1
        cursor.close()
        input('\n\npresione cualquier tecla para continuar.')

    """
    Método:         show_legends
    Parámetros:     Objeto Cursor, Objeto Query, Objeto View
    Retorno:        No retorna
    Funcionalidad:  Imprime por pantalla todos los pokemones legendarios.
    """
    def show_legends(self,connection,vw):
        cursor = connection.cursor()
        vw.legend(connection)
        cursor.execute('SELECT * FROM view_legendarys')
        legends = list()
        lista = cursor.fetchall()
        for tupla in lista:
            legends.append(tupla[2])
        size = len(legends)
        i = 0
        os.system('cls')
        print('\n===== BASE DE DATOS SANSANITO_POKEMON =====\n')
        print('\tLos pokemones legendarios son:\n')
        while i < size:
            if i < 9:
                print('\t'+str(i+1)+'.  '+legends[i]+'.')
            else:
                print('\t'+str(i+1)+'. '+legends[i]+'.')
            i += 1
        cursor.close()
        input('\n\npresione cualquier tecla para continuar.')

    """
    Método:         show_more_time
    Parámetros:     Objeto Cursor, Objeto Query
    Retorno:        No retorna
    Funcionalidad:  Imprime por pantalla el pokemon que lleva más tiempo ingresado
    """
    def show_more_time(self,connection,qr):
        cursor = connection.cursor()
        os.system('cls')
        pokemon = qr.moreTime(connection)
        print('\n===== BASE DE DATOS SANSANITO_POKEMON =====\n')
        print('\tEl pokemon que lleva más tiempo ingresado es '+pokemon)
        cursor.close()
        input('\n\npresione cualquier tecla para continuar.')

    """
    Método:         show_most_repeat
    Parámetros:     Objeto Cursor, Objeto Query
    Retorno:        No retorna
    Funcionalidad:  Imprime por pantalla el pokemon mas repetido
    """
    def show_most_repeat(self,connection,qr):
        cursor = connection.cursor()
        os.system('cls')
        pokemon = qr.mostRepeat(connection)
        print('\n===== BASE DE DATOS SANSANITO_POKEMON =====\n')
        print('\tEl pokemon mas repetido es '+pokemon)
        cursor.close()
        input('\n\npresione cualquier tecla para continuar.')

    """
    Método:         show_all
    Parámetros:     Objeto Cursor, Objeto Query
    Retorno:        No retorna
    Funcionalidad:  Imprime por pantalla el nombre, HP actual, HP max y prioridad de todos los pokemones, ordenado por prioridad
    """
    def show_all(self,connection,qr):
        cursor = connection.cursor()
        os.system('cls')
        info = qr.allInfo(connection)
        table = list()
        aux = list()
        for tupla in info:
            aux.append(tupla[1])
            aux.append(tupla[2])
            aux.append(tupla[3])
            aux.append(tupla[0])
            table.append(aux)
            aux = list()
        print('\n===== BASE DE DATOS SANSANITO_POKEMON =====\n')
        print(tabulate.tabulate(table,headers=['Nombre','HP actual','HP max','Prioridad'],tablefmt='rst'))
        cursor.close()
        input('\n\npresione cualquier tecla para continuar.')

    """
    Método:         read_menu
    Parámetros:     Objeto Cursor, Objeto Crud
    Retorno:        No retorna
    Funcionalidad:  Pide al usuario que elija entre los items (columnas) de la tabla SANSANITO_POKEMON y las muestra por pantalla
    """
    def read_menu(self,connection,crud):
        cursor = connection.cursor()
        os.system('cls')
        print('\n===== BASE DE DATOS SANSANITO_POKEMON =====\n')
        print('\tItems:\n')
        print('\t1.  ID.')
        print('\t2.  Numero de Pokedex.')
        print('\t3.  Nombre.')
        print('\t4.  Tipo 1.')
        print('\t5.  Tipo 2.')
        print('\t6.  HP actual.')
        print('\t7.  HP max.')
        print('\t8.  Legendario.')
        print('\t9.  Estado.')
        print('\t10. Fecha y hora de ingreso.')
        print('\t11. Prioridad.\n')
        opciones = input('\tDigite los numeros de los items que desee, separados por un guion: ')
        items = list()
        opciones += '-'
        aux = ''
        for caracter in opciones:
            if caracter != '-':
                aux += caracter
            else:
                items.append(int(aux))
                aux = ''
        headers = list()
        for number in items:
            if number == 1:
                headers.append('ID')
            if number == 2:
                headers.append('Numero de Pokedex')
            if number == 3:
                headers.append('Nombre')
            if number == 4:
                headers.append('Tipo 1')
            if number == 5:
                headers.append('Tipo 2')
            if number == 6:
                headers.append('HP actual')
            if number == 7:
                headers.append('HP max')
            if number == 8:
                headers.append('Legendario')
            if number == 9:
                headers.append('Estado')
            if number == 10:
                headers.append('Fecha y hora de ingreso')
            if number == 11:
                headers.append('Prioridad')
        info = crud.READ(connection,items)
        size = len(info[0])
        table = list()
        aux = list()
        i = 0
        while i < size:
            for tupla in info:
                aux.append(tupla[i])
            table.append(aux)
            aux = list()
            i += 1
        os.system('cls')
        print('\n===== BASE DE DATOS SANSANITO_POKEMON =====\n')
        print(tabulate.tabulate(table,headers=headers,tablefmt='rst'))
        cursor.close()
        input('\n\npresione cualquier tecla para continuar.')

    """
    Método:         update_menu
    Parámetros:     Objeto Connection, Objeto Crud, Objeto Function
    Retorno:        No retorna
    Funcionalidad:  Pide el nombre del pokemon a actualizar, los datos a actualizar y realiza la actualizacion
    """
    def update_menu(self,connection,crud,fun):
        os.system('cls')
        print('\n===== BASE DE DATOS SANSANITO_POKEMON =====\n')
        name = input('\tInserte nombre del pokemon a actualizar: ')
        os.system('cls')
        print('\n===== BASE DE DATOS SANSANITO_POKEMON =====\n')
        print('\tItems actualizables:')
        print('\t1. HP actual')
        print('\t2. Estado.\n')
        opciones = input('\tDigite los numeros de los items que desee, separados por un guion: ')
        aux = list()
        if '-' in opciones:
            aux.append(int(opciones[0]))
            aux.append(int(opciones[2]))
        else:
            aux.append(int(opciones))
        columns = dict()
        for number in aux:
            if number == 1:
                os.system('cls')
                print('\n===== BASE DE DATOS SANSANITO_POKEMON =====\n')
                actualHP = int(input('\tDigite nuevo HP actual: '))
                columns[number] = actualHP
                cursor = connection.cursor()
                cursor.execute('SELECT * FROM SANSANITO_POKEMON')
                info = cursor.fetchall()
                cursor.close()
                for tupla in info:
                    if name in tupla:
                        maxHP = tupla[6]
                        break
                prioridad = maxHP-actualHP
                columns[3] = prioridad
            elif number == 2:
                os.system('cls')
                print('\n===== BASE DE DATOS SANSANITO_POKEMON =====\n')
                state = input('\tIngrese el estado de '+name+" (enter para no ingresar estado): ")
                columns[number] = state
        crud.UPDATE(connection,columns,name,fun)
        os.system('cls')
        print('\n===== BASE DE DATOS SANSANITO_POKEMON =====\n')
        print('\tLos datos de '+name+' han sido actualizados.')
        input('\n\npresione cualquier tecla para continuar.')

    """
    Método:         delete_menu
    Parámetros:     Objeto Connection, Objeto Crud, Objeto Function
    Retorno:        No retorna
    Funcionalidad:  Pide el nombre del pokemon a eliminar.
    """
    def delete_menu(self,connection,crud,fun):
        os.system('cls')
        print('\n===== BASE DE DATOS SANSANITO_POKEMON =====\n')
        name = input('\tInserte nombre del pokemon a eliminar: ')
        crud.DELETE(connection,name,fun)
        os.system('cls')
        print('\n===== BASE DE DATOS SANSANITO_POKEMON =====\n')
        print('\tEl pokemon '+name+' ha sido eliminado.')
        input('\n\npresione cualquier tecla para continuar.')

class View:
    """
    Método:         legend
    Parámetros:     Objeto Connection
    Retorno:        No retorna
    Funcionalidad:  Crea una vista de los pokemones legendarios
    """
    def legend(self,connection):
        cursor = connection.cursor()
        cursor.execute("CREATE OR REPLACE VIEW view_legendarys AS (SELECT * FROM SANSANITO_POKEMON  WHERE Legendario='True')")
        cursor.close()

class Trigger:
    stmt1 = 'CREATE OR REPLACE TRIGGER validate_HP1 BEFORE INSERT ON SANSANITO_POKEMON FOR EACH ROW\n\t'
    stmt2 = 'BEGIN \n\t\tIF :new.HP_Actual < 0 THEN\n\t\t\t :new.HP_Actual := 0;\n\t\t'
    stmt3 = 'END IF;\n\t'
    stmt4 = 'END;'
    stmt5 = 'CREATE OR REPLACE TRIGGER validate_HP2 BEFORE UPDATE ON SANSANITO_POKEMON FOR EACH ROW\n\t'
    stmt6 = 'BEGIN \n\t\tIF :new.HP_Actual < 0 THEN\n\t\t\t :new.HP_Actual := 0;\n\t\t'
    stmt7 = 'END IF;\n\t'
    stmt8 = 'END;'

    """
    Método:         __init__
    Parámetros:     Objeto Connection
    Retorno:        Objeto Trigger
    Funcionalidad:  Constructor de la clase que al instanciar un objeto crea los triggers
    """
    def __init__(self,connection):
        cursor = connection.cursor()
        cursor.execute('{}{}{}{}'.format(self.stmt1,self.stmt2,self.stmt3,self.stmt4))
        cursor.execute('{}{}{}{}'.format(self.stmt5,self.stmt6,self.stmt7,self.stmt8))

"""""""""""""""""""""
PROGRAMA PRINCIPAL
"""""""""""""""""""""
#Establecer conexión
try:
    dsn_tns = cx_Oracle.makedsn('HOST', '1521', service_name='') #Introducir host, port y service_name
    connection = cx_Oracle.connect(user=r'', password='', dsn=dsn_tns) #Introducir usuario, contraseña y dsn_tns
except cx_Oracle.Error as error:
    print(error)
    input('presione cualquier tecla para continuar.')
else:
    #objetos necesarios
    fun  = Function()
    crud = Crud()
    qr   = Query()
    mn   = Menu()
    vw = View()
    trg = Trigger(connection)
    #Construcción de la base de datos
    lista_pokemones = fun.extractCSV()
    if not fun.existTable(connection,'POYO'):
        fun.createTable(connection,'POYO')

    if fun.tableIsEmpty(connection,'POYO'):
        fun.fillPOYO(connection,lista_pokemones)

    if not fun.existTable(connection,'SANSANITO_POKEMON'):
        fun.createSansanito(connection)

    #funcionamiento del menu, consultas y operaciones
    flag = True
    while flag:
        opcion = mn.main_menu()
        if opcion == 1:
            flag_query = True
            while flag_query:
                opcion = mn.querys_menu()
                if opcion == 1:#ingresar pokemon
                    mn.into_pokemon_menu(connection,crud,fun)
                    if not mn.repeat_menu('query'):
                        flag_query = False
                if opcion == 2:#top10
                    mn.show_top10(connection,qr)
                    if not mn.repeat_menu('query'):
                        flag_query = False
                if opcion == 3:#bottom10
                    mn.show_bottom10(connection,qr)
                    if not mn.repeat_menu('query'):
                        flag_query = False
                if opcion == 4:#todos los pokemones con un estado*
                    mn.show_state_pokemons(connection,qr)
                    if not mn.repeat_menu('query'):
                        flag_query = False
                if opcion == 5:#todos los legendarios*
                    mn.show_legends(connection,vw)
                    if not mn.repeat_menu('query'):
                        flag_query = False
                if opcion == 6:#pokemon mas tiempo ingresado
                    mn.show_more_time(connection,qr)
                    if not mn.repeat_menu('query'):
                        flag_query = False
                if opcion == 7:#nombre mas repetido
                    mn.show_most_repeat(connection,qr)
                    if not mn.repeat_menu('query'):
                        flag_query = False
                if opcion == 8:#datos de todos los pokemones
                    mn.show_all(connection,qr)
                    if not mn.repeat_menu('query'):
                        flag_query = False
                if opcion == 9:#menu principal
                    flag_query = False
        elif opcion == 2:
            flag_CRUD = True
            while flag_CRUD:
                opcion = mn.CRUD_menu()
                if opcion == 1:#CREATE
                    mn.into_pokemon_menu(connection,crud,fun)
                    if not mn.repeat_menu('CRUD'):
                        flag_CRUD = False
                if opcion == 2:#READ
                    mn.read_menu(connection,crud)
                    if not mn.repeat_menu('CRUD'):
                        flag_CRUD = False
                if opcion == 3:#UPDATE
                    mn.update_menu(connection,crud,fun)
                    if not mn.repeat_menu('CRUD'):
                        flag_CRUD = False
                if opcion == 4:#DELETE
                    mn.delete_menu(connection,crud,fun)
                    if not mn.repeat_menu('CRUD'):
                        flag_CRUD = False
                if opcion == 5:#menu principal
                    flag_CRUD = False
        elif opcion == 3:
            flag = False
finally:
    connection.close()
