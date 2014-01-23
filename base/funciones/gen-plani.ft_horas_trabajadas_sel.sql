CREATE OR REPLACE FUNCTION "plani"."ft_horas_trabajadas_sel"(	
				p_administrador integer, p_id_usuario integer, p_tabla character varying, p_transaccion character varying)
RETURNS character varying AS
$BODY$
/**************************************************************************
 SISTEMA:		Sistema de Planillas
 FUNCION: 		plani.ft_horas_trabajadas_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'plani.thoras_trabajadas'
 AUTOR: 		 (admin)
 FECHA:	        22-01-2014 16:11:12
 COMENTARIOS:	
***************************************************************************
 HISTORIAL DE MODIFICACIONES:

 DESCRIPCION:	
 AUTOR:			
 FECHA:		
***************************************************************************/

DECLARE

	v_consulta    		varchar;
	v_parametros  		record;
	v_nombre_funcion   	text;
	v_resp				varchar;
			    
BEGIN

	v_nombre_funcion = 'plani.ft_horas_trabajadas_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'PLA_HORTRA_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		admin	
 	#FECHA:		22-01-2014 16:11:12
	***********************************/

	if(p_transaccion='PLA_HORTRA_SEL')then
     				
    	begin
    		--Sentencia de la consulta
			v_consulta:='select
						hortra.id_horas_trabajadas,
						hortra.id_funcionario_planilla,
						hortra.id_uo_funcionario,
						hortra.tipo_contrato,
						hortra.estado_reg,
						hortra.horas_nocturnas,
						hortra.horas_disponibilidad,
						hortra.horas_extras,
						hortra.horas_normales,
						hortra.fecha_reg,
						hortra.id_usuario_reg,
						hortra.id_usuario_mod,
						hortra.fecha_mod,
						usu1.cuenta as usr_reg,
						usu2.cuenta as usr_mod	
						from plani.thoras_trabajadas hortra
						inner join segu.tusuario usu1 on usu1.id_usuario = hortra.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = hortra.id_usuario_mod
				        where  ';
			
			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;
						
		end;

	/*********************************    
 	#TRANSACCION:  'PLA_HORTRA_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		admin	
 	#FECHA:		22-01-2014 16:11:12
	***********************************/

	elsif(p_transaccion='PLA_HORTRA_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_horas_trabajadas)
					    from plani.thoras_trabajadas hortra
					    inner join segu.tusuario usu1 on usu1.id_usuario = hortra.id_usuario_reg
						left join segu.tusuario usu2 on usu2.id_usuario = hortra.id_usuario_mod
					    where ';
			
			--Definicion de la respuesta		    
			v_consulta:=v_consulta||v_parametros.filtro;

			--Devuelve la respuesta
			return v_consulta;

		end;
					
	else
					     
		raise exception 'Transaccion inexistente';
					         
	end if;
					
EXCEPTION
					
	WHEN OTHERS THEN
			v_resp='';
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje',SQLERRM);
			v_resp = pxp.f_agrega_clave(v_resp,'codigo_error',SQLSTATE);
			v_resp = pxp.f_agrega_clave(v_resp,'procedimientos',v_nombre_funcion);
			raise exception '%',v_resp;
END;
$BODY$
LANGUAGE 'plpgsql' VOLATILE
COST 100;
ALTER FUNCTION "plani"."ft_horas_trabajadas_sel"(integer, integer, character varying, character varying) OWNER TO postgres;
